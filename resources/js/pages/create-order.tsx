'use client';

import { Combobox } from '@/components/combobox';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import { create } from '@/routes/orders';
import { personnels as personnelsRoute, divisions as divisionsRoute, skus as skusRoute, customers as customersRoute, plants as plantsRoute} from '@/routes/api';
import { types as orderTypesRoute } from '@/routes/api/order';
import { type BreadcrumbItem } from '@/types';
import { zodResolver } from '@hookform/resolvers/zod';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { format } from 'date-fns';
import { CalendarIcon } from 'lucide-react';
import { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import * as z from 'zod';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Create Order',
        href: create().url,
    },
];

const formSchema = z.object({
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
    sku_code: z.string({
        error: 'Please select a SKU.',
    }),
    remarks: z.string().optional(),
    delivery_date: z.date({
        error: 'A delivery date is required.',
    }),
    delivery_mode: z.string({
        error: 'You need to select a delivery mode.',
    }),
    quantity: z.string({
        error: 'Please input a quantity.',
    }),
});

export default function CreateOrder() {
    console.log('CreateOrder Rendered');
    
    const form = useForm<z.infer<typeof formSchema>>({
        resolver: zodResolver(formSchema),
    });

    function onSubmit(values: z.infer<typeof formSchema>) {
        console.log(values);
    }

    const [orderTypes, setOrderTypes] = useState<{ value: string; label: string }[]>([]);
    const [personnels, setPersonnels] = useState<{ value: string; label: string }[]>([]);
    const [branchPlants, setBranchPlants] = useState<{ value: string; label: string }[]>([]);
    const [divisions, setDivisions] = useState<{ value: string; label: string }[]>([]);
    const [customers, setCustomers] = useState<{ value: string; label: string }[]>([]);
    const [skus, setSkus] = useState<{ value: string; label: string }[]>([]);
    const [isOrderTypeLoading, setIsOrderTypeLoading] = useState(true);
    const [isPersonnelsLoading, setIsPersonnelsLoading] = useState(true);
    const [isBranchPlantLoading, setIsBranchPlantLoading] = useState(true);
    const [isCustomerLoading, setIsCustomerLoading] = useState(false);
    const [isDivisionLoading, setIsDivisionLoading] = useState(false);
    const [isSkuLoading, setIsSkuLoading] = useState(false);

    useEffect(() => {
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
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                    <div className="relative col-span-2 overflow-hidden rounded-xl border border-sidebar-border/70 p-8.5 dark:border-sidebar-border">
                        <div className="mb-10 flex items-baseline justify-between">
                            <div>
                                <h2 className="text-base leading-7 font-semibold">Order Details</h2>
                                <p className="mt-1 text-sm leading-6 text-gray-400">Provide the main details for the order.</p>
                            </div>
                            <div>
                                <span className="text-sm font-medium">Order Slip #:</span>
                                <span className="ml-2 font-mono text-lg" x-text="orderSlipNumber">
                                    ORD-20254-01215
                                </span>
                            </div>
                        </div>
                        <Form {...form}>
                            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-8">
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
                                                        // value={field.value}
                                                        onChange={(value) => {
                                                            console.log(value);
                                                            
                                                            field.onChange(value);
                                                            populateDivisionOptions(value);
                                                        }}
                                                        placeholder={isOrderTypeLoading ? 'Loading personnels...' : 'Select personnel...'}
                                                        searchPlaceholder="Search personnel..."
                                                        disabled={isPersonnelsLoading}
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
                                                        // value={field.value}
                                                        onChange={field.onChange}
                                                        placeholder={isOrderTypeLoading ? "Loading order types..." : "Select order type..."}
                                                        searchPlaceholder="Search order type..."
                                                        disabled={isOrderTypeLoading}
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
                                                        // value={field.value}
                                                        onChange={(value) => {
                                                            field.onChange(value);
                                                            populateCustomerOptions(value);
                                                        }}
                                                        placeholder={
                                                            isDivisionLoading
                                                                ? "Loading divisions..." 
                                                                : "Select division..."
                                                        }
                                                        searchPlaceholder="Search division..."
                                                        disabled={isDivisionLoading}
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
                                                        // value={field.value}
                                                        onChange={(value) => {
                                                            field.onChange(value);
                                                            populateSkuOptions();
                                                        }}
                                                        placeholder={
                                                            isCustomerLoading
                                                                ? 'Loading customers...'
                                                                : 'Select customer...'
                                                        }
                                                        searchPlaceholder="Search customer..."
                                                        disabled={isCustomerLoading}
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
                                                        // value={field.value}
                                                        onChange={(value) => {
                                                            field.onChange(value);
                                                            populateSkuOptions();
                                                        }}
                                                        placeholder={isBranchPlantLoading ? "Loading branch plant..." : "Select branch plant..."}
                                                        searchPlaceholder="Search branch plant..."
                                                        disabled={isBranchPlantLoading}
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
                                                                    variant={'outline'}
                                                                    className={cn(
                                                                        'w-full pl-3 text-left font-normal aria-invalid:border-ring',
                                                                        !field.value && 'text-muted-foreground',
                                                                    )}
                                                                >
                                                                    {field.value ? format(field.value, 'PPP') : <span>Pick a date</span>}
                                                                    <CalendarIcon className="ml-auto h-4 w-4 opacity-50" />
                                                                </Button>
                                                            </FormControl>
                                                        </PopoverTrigger>
                                                        <PopoverContent className="w-auto p-0" align="start">
                                                            <Calendar
                                                                mode="single"
                                                                selected={field.value}
                                                                onSelect={field.onChange}
                                                                disabled={(date) => date > new Date() || date < new Date('1900-01-01')}
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
                                                        <RadioGroup onValueChange={field.onChange} defaultValue={field.value} className="">
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem value="pickup" className='aria-invalid:border-grey' />
                                                                </FormControl>
                                                                <FormLabel className="font-normal data-[error=true]:text-primary">Pick-up</FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem value="delivery" className='aria-invalid:border-grey' />
                                                                </FormControl>
                                                                <FormLabel className="font-normal data-[error=true]:text-primary">Delivery</FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem value="sea_shippment" className='aria-invalid:border-grey' />
                                                                </FormControl>
                                                                <FormLabel className="font-normal data-[error=true]:text-primary">Shipment by Sea</FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem value="air_shipment" className='aria-invalid:border-grey' />
                                                                </FormControl>
                                                                <FormLabel className="font-normal data-[error=true]:text-primary">Shipment by Air</FormLabel>
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
                                                        <Input placeholder="Enter remarks..." {...field} />
                                                    </FormControl>
                                                    <FormMessage />
                                                </FormItem>
                                            )}
                                        />
                                    </div>
                                </div>
                                <div className="border-b border-white/10 pb-12">
                                    <h2 className="text-base leading-7 font-semibold">Add Items to Order</h2>
                                    <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div className="sm:col-span-3">
                                            {/* SKU */}
                                            <FormField
                                                control={form.control}
                                                name="sku_code"
                                                render={({ field }) => (
                                                    <FormItem className="flex flex-col sm:col-span-2">
                                                        <FormLabel>SKU</FormLabel>
                                                        <Combobox
                                                            options={skus}
                                                            // value={field.value}
                                                            onChange={field.onChange}
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
                                                <p className="text-white-disabled mt-3.5 h-[2.125rem] leading-[1.5]">-</p>
                                            </div>
                                        </div>
                                        <div className="sm:col-span-1">
                                            <FormLabel>Unit Price</FormLabel>
                                            <div className="mt-auto">
                                                <p className="text-white-disabled mt-3.5 h-[2.125rem] leading-[1.5]">-</p>
                                            </div>
                                        </div>
                                        <div className="sm:col-span-1">
                                            <div className="mt-auto">
                                                {/* Remarks */}
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
                                        <div className="pt-4 sm:col-span-6 flex justify-end">
                                            <div className="flex items-end gap-x-4">
                                                <Button type="button" className='bg-slate-500'>Add to Cart</Button>
                                                <Button type="button" className='bg-red-500'>Clear</Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div className='flex justify-end gap-4'>
                                    <Button type="submit" className='bg-slate-500'>Save as Draft</Button>
                                    <Button type="submit">Submit</Button>
                                </div>
                            </form>
                        </Form>
                    </div>
                    <div className="relative col-span-1 overflow-hidden rounded-xl border border-sidebar-border/70 p-8.5 dark:border-sidebar-border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
