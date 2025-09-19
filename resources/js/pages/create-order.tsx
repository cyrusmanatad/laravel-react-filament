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
    quantity: z.number({
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
    const [isCustomerLoading, setIsCustomerLoading] = useState(false);
    const [isDivisionLoading, setIsDivisionLoading] = useState(false);
    const skus = [{ value: 'sku1', label: 'SKU 1' }];

    useEffect(() => {
        axios
            .get('/order-type')
            .then((response) => setOrderTypes(response.data))
            .catch((error) => console.error('Error fetching options:', error));

        axios
            .get('/personnels')
            .then((response) => setPersonnels(response.data))
            .catch((error) => console.error('Error fetching options:', error));

        axios
            .get('/plants')
            .then((response) => setBranchPlants(response.data))
            .catch((error) => console.error('Error fetching options:', error));
    }, []);

    const populateDivisionOptions = (psrCode: string) => {
        form.setValue('div_code', ''); // Reset division
        
        if (psrCode) {
            setIsDivisionLoading(true);
            axios
                .post('/divisions', {
                    personnel_code: psrCode,
                }) // Assuming an API route like this
                .then((response) => {
                    setDivisions(response.data);
                })
                .catch((error) => {
                    console.error('Error fetching customers:', error);
                    setDivisions([]);
                })
                .finally(() => {
                    setIsDivisionLoading(false);
                });
        } else {
            setDivisions([]);
        }
    };

    const populateCustomerOptions = (custCode: string) => {
        form.setValue('div_code', ''); // Reset division
        
        if (custCode) {
            setIsCustomerLoading(true);
            axios
                .post('/customers', {
                    personnel_code: form.getValues('psr_code'),
                    division_code: custCode,
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
                                                        placeholder="Select personnel..."
                                                        searchPlaceholder="Search personnel..."
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
                                                        placeholder="Select order type..."
                                                        searchPlaceholder="Search order type..."
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
                                                        disabled={isDivisionLoading || divisions.length === 0}
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
                                                        onChange={field.onChange}
                                                        placeholder={
                                                            isCustomerLoading
                                                                ? 'Loading customers...'
                                                                : 'Select customer...'
                                                        }
                                                        searchPlaceholder="Search customer..."
                                                        disabled={isCustomerLoading || customers.length === 0}
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
                                                        onChange={field.onChange}
                                                        placeholder="Select branch plant..."
                                                        searchPlaceholder="Search branch plant..."
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
                                                                        'w-full pl-3 text-left font-normal',
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
                                                                    <RadioGroupItem value="pickup" />
                                                                </FormControl>
                                                                <FormLabel className="font-normal">Pick-up</FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem value="delivery" />
                                                                </FormControl>
                                                                <FormLabel className="font-normal">Delivery</FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem value="sea_shippment" />
                                                                </FormControl>
                                                                <FormLabel className="font-normal">Shipment by Sea</FormLabel>
                                                            </FormItem>
                                                            <FormItem className="flex items-center space-y-0 space-x-3">
                                                                <FormControl>
                                                                    <RadioGroupItem value="air_shipment" />
                                                                </FormControl>
                                                                <FormLabel className="font-normal">Shipment by Air</FormLabel>
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
                                                            placeholder="Select SKU..."
                                                            searchPlaceholder="Search SKU..."
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
                                        <div className="pt-4 sm:col-span-6">
                                            <div className="flex items-end gap-x-4">
                                                <Button type="button">Add to Cart</Button>
                                                <Button type="button">Clear</Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <Button type="submit">Submit</Button>
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
