'use client';

import { Combobox } from '@/components/combobox';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import {
    customers as customersRoute,
    divisions as divisionsRoute,
    personnels as personnelsRoute,
    plants as plantsRoute,
    skus as skusRoute,
} from '@/routes/api';
import { types as orderTypesRoute } from '@/routes/api/order';
import { create, store } from '@/routes/orders';
import { type BreadcrumbItem } from '@/types';
import { zodResolver } from '@hookform/resolvers/zod';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { format } from 'date-fns';
import { CalendarIcon, Check, Pencil, Trash2, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { Toaster, toast } from 'sonner'
import * as z from 'zod';

type Option = { value: string; label: string };
type SkuOption = Option & { uom: string; unit_price: string };
type CartItem = {
    sku_code: string;
    sku_label: string;
    uom: string;
    unit_price: string;
    quantity: string;
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Order',
        href: create().url,
    },
];

const formSchema = z.object({
    psr_uid: z.string(),
    order_slip_number: z.string(),
    order_type: z.string({
        error: 'Please select an order type.',
    }),
    psr_code: z.string({
        error: 'Please select a personnel.',
    }),
    div_code: z.string({
        error: 'Please select a division.',
    }),
    cust_code: z.string({
        error: 'Please select a customer.',
    }),
    branch_code: z.string({
        error: 'Please select a branch plant.',
    }),
    sku_code: z.string().optional(),
    uom: z.string().optional(),
    unit_price: z.string().optional(),
    remarks: z.string().optional(),
    delivery_date: z.date({
        error: 'A delivery date is required.',
    }),
    delivery_mode: z.string({
        error: 'You need to select a delivery mode.',
    }),
    quantity: z.string().optional(),
});

export default function CreateOrder() {
    const form = useForm<z.infer<typeof formSchema>>({
        resolver: zodResolver(formSchema),
        defaultValues: {
            uom: '-',
            unit_price: '-',
        },
    });

    const uom = form.watch('uom');
    const unitPrice = form.watch('unit_price');

    const [orderSlipNumber, setOrderSlipNumber] = useState<string>('');
    const [cartItems, setCartItems] = useState<CartItem[]>([]);
    const [orderTypes, setOrderTypes] = useState<Option[]>([]);
    const [personnels, setPersonnels] = useState<Option[]>([]);
    const [branchPlants, setBranchPlants] = useState<Option[]>([]);
    const [divisions, setDivisions] = useState<Option[]>([]);
    const [customers, setCustomers] = useState<Option[]>([]);
    const [skus, setSkus] = useState<SkuOption[]>([]);
    const [isOrderTypeLoading, setIsOrderTypeLoading] = useState<boolean>(true);
    const [isPersonnelsLoading, setIsPersonnelsLoading] = useState<boolean>(true);
    const [isBranchPlantLoading, setIsBranchPlantLoading] = useState<boolean>(true);
    const [isCustomerLoading, setIsCustomerLoading] = useState<boolean>(false);
    const [isDivisionLoading, setIsDivisionLoading] = useState<boolean>(false);
    const [isSkuLoading, setIsSkuLoading] = useState<boolean>(false);
    const [editingItemIndex, setEditingItemIndex] = useState<number | null>(null);
    const [editingQuantity, setEditingQuantity] = useState<string>('');

    const handleAddToCart = () => {
        const { sku_code, quantity } = form.getValues();

        if (!sku_code) {
            form.setError('sku_code', { type: 'manual', message: 'Please select a SKU.' });
            return;
        }

        const quantityToAdd = parseInt(quantity || '0');
        if (quantityToAdd < 1) {
            form.setError('quantity', { type: 'manual', message: 'Please input a valid quantity.' });
            return;
        }

        const existingItemIndex = cartItems.findIndex((item) => item.sku_code === sku_code);

        if (existingItemIndex > -1) {
            const updatedCartItems = [...cartItems];
            const existingItem = updatedCartItems[existingItemIndex];
            const newQuantity = parseInt(existingItem.quantity) + quantityToAdd;
            updatedCartItems[existingItemIndex] = { ...existingItem, quantity: newQuantity.toString() };
            setCartItems(updatedCartItems);
        } else {
            const selectedSku = skus.find((sku) => sku.value === sku_code);
            if (selectedSku) {
                const newItem: CartItem = {
                    sku_code,
                    sku_label: selectedSku.label,
                    uom: form.getValues('uom') || '-',
                    unit_price: form.getValues('unit_price') || '-',
                    quantity: quantityToAdd.toString(),
                };
                setCartItems([...cartItems, newItem]);
            }
        }

        toast("Item has been added to cart.", {
            description:  cartItems[cartItems.length-1].sku_label
        })
        form.resetField('sku_code');
        form.resetField('uom');
        form.resetField('unit_price');
        form.setValue('quantity', '');
        form.setValue('uom', '-');
        form.setValue('unit_price', '-');
    };

    const handleClearItemSelection = () => {
        form.resetField('psr_code');
        form.resetField('order_type');
        form.resetField('div_code');
        setDivisions([]);
        form.resetField('cust_code');
        setCustomers([]);
        form.resetField('branch_code');
        setBranchPlants([]);
        form.resetField('delivery_date');
        form.resetField('delivery_mode');
        form.resetField('sku_code');
        setSkus([]);
        form.resetField('uom');
        form.resetField('unit_price');
        form.setValue('remarks', '');
        form.setValue('quantity', '');
        form.setValue('uom', '-');
        form.setValue('unit_price', '-');
        setCartItems([]);
    };

    const handleRemoveItem = (index: number) => {
        setCartItems(cartItems.filter((_, i) => i !== index));
    };

    const handleEditItem = (index: number) => {
        setEditingItemIndex(index);
        setEditingQuantity(cartItems[index].quantity);
    };

    const handleCancelEdit = () => {
        setEditingItemIndex(null);
        setEditingQuantity('');
    };

    const handleUpdateQuantity = (index: number) => {
        const newQuantity = parseInt(editingQuantity);
        if (isNaN(newQuantity) || newQuantity < 1) {
            alert('Please enter a valid quantity.');
            return;
        }

        const updatedCartItems = [...cartItems];
        updatedCartItems[index].quantity = editingQuantity;
        setCartItems(updatedCartItems);

        setEditingItemIndex(null);
        setEditingQuantity('');
    };

    function handleSaveDraft(values: z.infer<typeof formSchema>) {
        if (cartItems.length === 0) {
            alert('Please add at least one item to the order.');
            return;
        }
        console.log('Saving draft:', { ...values, items: cartItems });
        // Here you would typically make an API call to save the draft
    }

    function handleSubmitOrder(values: z.infer<typeof formSchema>) {
        if (cartItems.length === 0) {
            alert('Please add at least one item to the order.');
            return;
        }
        axios
            .post(store().url, { ...values, items: cartItems })
            .then((response) => {
                console.log(response);
                handleClearItemSelection();
                setOrderSlipNumber(generateOrderSlipNumber)
                toast("Order has been submitted.")
            })
            .catch((error) => console.error('Error fetching options:', error))
    }

    function generateOrderSlipNumber() {
        const _date = new Date();
        return "W1100-" + _date.getFullYear().toString().substring(2)  +"-"+ _date.getMonth() + _date.getHours() + _date.getMinutes() + _date.getSeconds()
    }

    useEffect(() => {
        setOrderSlipNumber(generateOrderSlipNumber)

        axios
            .get(orderTypesRoute().url)
            .then((response) => setOrderTypes(response.data))
            .catch((error) => console.error('Error fetching options:', error))
            .finally(() => setIsOrderTypeLoading(false));

        axios
            .get(personnelsRoute().url)
            .then((response) => setPersonnels(response.data))
            .catch((error) => console.error('Error fetching options:', error))
            .finally(() => setIsPersonnelsLoading(false));

        axios
            .get(plantsRoute().url)
            .then((response) => setBranchPlants(response.data))
            .catch((error) => console.error('Error fetching options:', error))
            .finally(() => setIsBranchPlantLoading(false));
    }, []);

    form.setValue('order_slip_number', orderSlipNumber);
    form.setValue('psr_uid', '9999');

    const populateDivisionOptions = (psrCode: string) => {
        form.setValue('div_code', ''); // Reset division
        form.setValue('cust_code', ''); // Reset customer
        setCustomers([]);
        setSkus([]);

        if (psrCode) {
            setIsDivisionLoading(true);
            axios
                .post(divisionsRoute().url, {
                    personnel_code: psrCode,
                }) // Assuming an API route like this
                .then((response) => {
                    setDivisions(response.data);
                })
                .catch((error) => {
                    console.error('Error fetching divisions:', error);
                    setDivisions([]);
                })
                .finally(() => {
                    setIsDivisionLoading(false);
                });
        } else {
            setDivisions([]);
        }
    };

    const populateCustomerOptions = (divisionCode: string) => {
        form.setValue('cust_code', ''); // Reset customer
        setSkus([]);

        if (divisionCode) {
            setIsCustomerLoading(true);
            axios
                .post(customersRoute().url, {
                    personnel_code: form.getValues('psr_code'),
                    division_code: divisionCode,
                }) // Assuming an API route like this
                .then((response) => {
                    setCustomers(response.data);
                })
                .catch((error) => {
                    console.error('Error fetching customers:', error);
                    setCustomers([]);
                })
                .finally(() => {
                    setIsCustomerLoading(false);
                });
        } else {
            setCustomers([]);
        }
    };

    const populateSkuOptions = () => {
        const divCode = form.getValues('div_code');
        const custCode = form.getValues('cust_code');
        const branchCode = form.getValues('branch_code');

        if (divCode && custCode && branchCode) {
            setIsSkuLoading(true);
            axios
                .post(skusRoute().url, {
                    division_code: divCode,
                    customer_code: custCode,
                    branch_code: branchCode,
                })
                .then((response) => {
                    setSkus(response.data);
                })
                .catch((error) => {
                    console.error('Error fetching SKUs:', error);
                    setSkus([]);
                })
                .finally(() => {
                    setIsSkuLoading(false);
                });
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Order" />
            <Toaster position="top-center"/>
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="relative col-span-2 overflow-hidden rounded-xl border border-sidebar-border/70 p-8.5 dark:border-sidebar-border">
                        <div className="mb-10 flex items-baseline justify-between">
                            <div>
                                <h2 className="text-base font-semibold leading-7">Order Details</h2>
                                <p className="mt-1 text-sm leading-6 text-gray-400">
                                    Provide the main details for the order.
                                </p>
                            </div>
                            <div>
                                <span className="text-sm font-medium">Order Slip #:</span>
                                <span className="ml-2 font-mono text-lg" x-text="orderSlipNumber">
                                    {orderSlipNumber}
                                </span>
                            </div>
                        </div>
                        <Form {...form}>
                            <form className="space-y-8">
                                <div className="border-gray/10 border-b pb-12">
                                    <div className="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        {/* Personnel */}
                                        <FormField
                                            control={form.control}
                                            name="psr_code"
                                            render={({ field }) => (
                                                <FormItem className="flex flex-col sm:col-span-2">
                                                    <FormLabel>Personnel</FormLabel>
                                                    <Combobox
                                                        options={personnels}
                                                        value={field.value}
                                                        onChange={(value) => {
                                                            field.onChange(value);
                                                            populateDivisionOptions(value);
                                                        }}
                                                        placeholder={
                                                            isOrderTypeLoading
                                                                ? 'Loading personnels...'
                                                                : 'Select personnel...'
                                                        }
                                                        searchPlaceholder="Search personnel..."
                                                        disabled={isPersonnelsLoading || cartItems.length !== 0}
                                                    />
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                        {/* Order Type */}
                                        <FormField
                                            control={form.control}
                                            name="order_type"
                                            render={({ field }) => (
                                                <FormItem className="flex flex-col sm:col-span-2">
                                                    <FormLabel>Order Type</FormLabel>
                                                    <Combobox
                                                        options={orderTypes}
                                                        value={field.value}
                                                        onChange={field.onChange}
                                                        placeholder={
                                                            isOrderTypeLoading
                                                                ? 'Loading order types...'
                                                                : 'Select order type...'
                                                        }
                                                        searchPlaceholder="Search order type..."
                                                        disabled={isOrderTypeLoading || cartItems.length !== 0}
                                                    />
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                        {/* Division */}
                                        <FormField
                                            control={form.control}
                                            name="div_code"
                                            render={({ field }) => (
                                                <FormItem className="flex flex-col sm:col-span-2">
                                                    <FormLabel>Division</FormLabel>
                                                    <Combobox
                                                        options={divisions}
                                                        value={field.value}
                                                        onChange={(value) => {
                                                            field.onChange(value);
                                                            populateCustomerOptions(value);
                                                        }}
                                                        placeholder={
                                                            isDivisionLoading ? 'Loading divisions...' : 'Select division...'
                                                        }
                                                        searchPlaceholder="Search division..."
                                                        disabled={isDivisionLoading || cartItems.length !== 0}
                                                    />
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                        {/* Customer */}
                                        <FormField
                                            control={form.control}
                                            name="cust_code"
                                            render={({ field }) => (
                                                <FormItem className="flex flex-col sm:col-span-4">
                                                    <FormLabel>Customer</FormLabel>
                                                    <Combobox
                                                        options={customers}
                                                        value={field.value}
                                                        onChange={(value) => {
                                                            field.onChange(value);
                                                            populateSkuOptions();
                                                        }}
                                                        placeholder={
                                                            isCustomerLoading ? 'Loading customers...' : 'Select customer...'
                                                        }
                                                        searchPlaceholder="Search customer..."
                                                        disabled={isCustomerLoading || cartItems.length !== 0}
                                                    />
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                        {/* Branch Plant */}
                                        <FormField
                                            control={form.control}
                                            name="branch_code"
                                            render={({ field }) => (
                                                <FormItem className="flex flex-col sm:col-span-3">
                                                    <FormLabel>Branch Plant</FormLabel>
                                                    <Combobox
                                                        options={branchPlants}
                                                        value={field.value}
                                                        onChange={(value) => {
                                                            field.onChange(value);
                                                            populateSkuOptions();
                                                        }}
                                                        placeholder={
                                                            isBranchPlantLoading
                                                                ? 'Loading branch plant...'
                                                                : 'Select branch plant...'
                                                        }
                                                        searchPlaceholder="Search branch plant..."
                                                        disabled={isBranchPlantLoading || cartItems.length !== 0}
                                                    />
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                        {/* Delivery Date */}
                                        <FormField
                                            control={form.control}
                                            name="delivery_date"
                                            render={({ field }) => (
                                                <FormItem className="flex flex-col sm:col-span-2">
                                                    <FormLabel>Delivery Date</FormLabel>
                                                    <Popover>
                                                        <PopoverTrigger asChild>
                                                            <FormControl>
                                                                <Button
                                                                    disabled={cartItems.length !== 0}
                                                                    variant={'outline'}
                                                                    className={cn(
                                                                        'w-full pl-3 text-left font-normal aria-invalid:border-ring',
                                                                        !field.value && 'text-muted-foreground',
                                                                    )}
                                                                >
                                                                    {field.value ? (
                                                                        format(field.value, 'PPP')
                                                                    ) : (
                                                                        <span>Pick a date</span>
                                                                    )}
                                                                    <CalendarIcon className="ml-auto h-4 w-4 opacity-50" />
                                                                </Button>
                                                            </FormControl>
                                                        </PopoverTrigger>
                                                        <PopoverContent className="w-auto p-0" align="start">
                                                            <Calendar
                                                                mode="single"
                                                                selected={field.value}
                                                                onSelect={field.onChange}
                                                                disabled={(date) =>
                                                                    date > new Date() || date < new Date('1900-01-01') || cartItems.length !== 0
                                                                }
                                                                // initialFocus
                                                            />
                                                        </PopoverContent>
                                                    </Popover>
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                        {/* Delivery Mode */}
                                        <FormField
                                            control={form.control}
                                            name="delivery_mode"
                                            render={({ field }) => (
                                                <FormItem className="col-span-full">
                                                    <FormLabel>Delivery Mode</FormLabel>
                                                    <FormControl className="flex space-y-1 gap-x-8 gap-y-4">
                                                        <RadioGroup
                                                            onValueChange={field.onChange}
                                                            defaultValue={field.value}
                                                            className=""
                                                            disabled={cartItems.length !== 0}
                                                        >
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem
                                                                        value="pickup"
                                                                        className="aria-invalid:border-grey"
                                                                    />
                                                                </FormControl>
                                                                <FormLabel className="font-normal data-[error=true]:text-primary">
                                                                    Pick-up
                                                                </FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem
                                                                        value="delivery"
                                                                        className="aria-invalid:border-grey"
                                                                    />
                                                                </FormControl>
                                                                <FormLabel className="font-normal data-[error=true]:text-primary">
                                                                    Delivery
                                                                </FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem
                                                                        value="sea_shippment"
                                                                        className="aria-invalid:border-grey"
                                                                    />
                                                                </FormControl>
                                                                <FormLabel className="font-normal data-[error=true]:text-primary">
                                                                    Shipment by Sea
                                                                </FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem
                                                                        value="air_shipment"
                                                                        className="aria-invalid:border-grey"
                                                                    />
                                                                </FormControl>
                                                                <FormLabel className="font-normal data-[error=true]:text-primary">
                                                                    Shipment by Air
                                                                </FormLabel>
                                                            </FormItem>
                                                        </RadioGroup>
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                        {/* Remarks */}
                                        <FormField
                                            control={form.control}
                                            name="remarks"
                                            render={({ field }) => (
                                                <FormItem className="col-span-full flex flex-col">
                                                    <FormLabel>Remarks</FormLabel>
                                                    <FormControl>
                                                        <Input disabled={cartItems.length !== 0} placeholder="Enter remarks..." {...field} />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                    </div>
                                </div>
                                <div className="border-b border-white/10 pb-12">
                                    <h2 className="text-base font-semibold leading-7">Add Items to Order</h2>
                                    <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div className="sm:col-span-3">
                                            {/* SKU */}
                                            <FormField
                                                control={form.control}
                                                name="sku_code"
                                                render={({ field }) => (
                                                    <FormItem className="flex flex-col">
                                                        <FormLabel>SKU</FormLabel>
                                                        <Combobox
                                                            options={skus}
                                                            value={field.value}
                                                            onChange={(value) => {
                                                                field.onChange(value);
                                                                const selectedSku = skus.find(
                                                                    (sku) => sku.value === value,
                                                                );
                                                                if (selectedSku) {
                                                                    form.setValue('uom', selectedSku.uom);
                                                                    form.setValue(
                                                                        'unit_price',
                                                                        selectedSku.unit_price,
                                                                    );
                                                                } else {
                                                                    form.setValue('uom', '-');
                                                                    form.setValue('unit_price', '-');
                                                                }
                                                            }}
                                                            placeholder={isSkuLoading ? 'Loading SKUs...' : 'Select SKU...'}
                                                            searchPlaceholder="Search SKU..."
                                                            disabled={isSkuLoading}
                                                        />
                                                        <FormMessage />
                                                    </FormItem>
                                                )}
                                            />
                                        </div>
                                        <div className="sm:col-span-1">
                                            <FormLabel>UOM</FormLabel>
                                            <div className="mt-auto">
                                                <p className="text-white-disabled mt-3.5 h-[2.125rem] leading-[1.5]">
                                                    {uom}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="sm:col-span-1">
                                            <FormLabel>Unit Price</FormLabel>
                                            <div className="mt-auto">
                                                <p className="text-white-disabled mt-3.5 h-[2.125rem] leading-[1.5]">
                                                    {unitPrice}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="sm:col-span-1">
                                            <div className="mt-auto">
                                                <FormField
                                                    control={form.control}
                                                    name="quantity"
                                                    render={({ field }) => (
                                                        <FormItem className="col-span-full flex flex-col">
                                                            <FormLabel>Quantity</FormLabel>
                                                            <FormControl>
                                                                <Input type="number" min={1} {...field} />
                                                            </FormControl>
                                                            <FormMessage />
                                                        </FormItem>
                                                    )}
                                                />
                                            </div>
                                        </div>
                                        <div className="flex justify-end pt-4 sm:col-span-6">
                                            <div className="flex items-end gap-x-4">
                                                <Button type="button" onClick={form.handleSubmit(handleAddToCart)}
                                                    className="cursor-pointer"
                                                >
                                                    Add to Cart
                                                </Button>
                                                <Button
                                                    type="button"
                                                    className="bg-red-500 cursor-pointer"
                                                    onClick={handleClearItemSelection}
                                                >
                                                    Clear
                                                </Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div className="flex justify-end gap-4">
                                    <Button type="button" onClick={form.handleSubmit(handleSaveDraft)}
                                        className="bg-slate-500 cursor-pointer"
                                    >
                                        Save as Draft
                                    </Button>
                                    <Button type="button" onClick={form.handleSubmit(handleSubmitOrder)} className="cursor-pointer">
                                        Submit
                                    </Button>
                                </div>
                            </form>
                        </Form>
                    </div>
                    <div className="relative col-span-1 flex flex-col gap-y-4 overflow-hidden rounded-xl border border-sidebar-border/70 p-8.5 dark:border-sidebar-border">
                        <h2 className="text-base font-semibold leading-7">Order Summary</h2>
                        {cartItems.length === 0 ? (
                            <div className="relative flex h-full w-full items-center justify-center rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700">
                                <p className="text-muted-foreground">No items in cart</p>
                            </div>
                        ) : (
                            <div className="flex flex-col gap-y-4">
                                {cartItems.map((item, index) => (
                                    <div key={index} className="rounded-lg border border-gray-200/10">
                                        <div className="flex items-center justify-between">
                                            <small className="font-semibold">{item.sku_label}</small>
                                            <div>
                                                {editingItemIndex === index ? (
                                                    <div className="flex items-center justify-end gap-x-1">
                                                        <Button
                                                            variant="ghost"
                                                            size="icon"
                                                            onClick={() => handleUpdateQuantity(index)}
                                                        >
                                                            <Check className="h-4 w-4" />
                                                        </Button>
                                                        <Button
                                                            variant="ghost"
                                                            size="icon"
                                                            onClick={handleCancelEdit}
                                                        >
                                                            <X className="h-4 w-4" />
                                                        </Button>
                                                    </div>
                                                ) : (
                                                    <div className="flex items-center justify-end gap-x-1">
                                                        <Button
                                                            variant="ghost"
                                                            size="icon"
                                                            onClick={() => handleEditItem(index)}
                                                        >
                                                            <Pencil className="h-4 w-4" />
                                                        </Button>
                                                        <Button
                                                            variant="ghost"
                                                            size="icon"
                                                            onClick={() => handleRemoveItem(index)}
                                                        >
                                                            <Trash2 className="h-4 w-4" />
                                                        </Button>
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                        <div className="flex justify-between text-sm">
                                            <div>
                                                <span className="text-muted-foreground">Qty: </span>
                                                {editingItemIndex === index ? (
                                                    <Input
                                                        type="number"
                                                        value={editingQuantity}
                                                        onChange={(e) => setEditingQuantity(e.target.value)}
                                                        className="h-8 w-20"
                                                        min={1}
                                                    />
                                                ) : (
                                                    <>
                                                        <span className="font-medium font-mono mx-2">{item.quantity}</span>
                                                        |
                                                        <span className="font-medium font-mono mx-2 text-muted-foreground">{item.uom}</span>
                                                    </>
                                                )}
                                            </div>
                                            <div className="text-right">
                                                <p className='font-mono'>@ {parseFloat(item.unit_price).toFixed(2)}</p>
                                                {/* <p className="text-muted-foreground">({item.uom})</p> */}
                                            </div>
                                        </div>
                                        <hr className='mt-2' />
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}