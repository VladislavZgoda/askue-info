import { Link, router } from '@inertiajs/react';
import { useDebouncedCallback } from '@tanstack/react-pacer/debouncer';
import { Eye, LoaderIcon, ParkingMeter, Search, X } from 'lucide-react';
import { useEffect, useState } from 'react';

import { create, index } from '@/actions/App/Http/Controllers/MeterController';
import { Button } from '@/components/ui/button';
import { InputGroup, InputGroupAddon, InputGroupButton, InputGroupInput } from '@/components/ui/input-group';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemTitle } from '@/components/ui/item';
import { ScrollArea } from '@/components/ui/scroll-area';
import type { MetersProps } from '@/types';

export default function Index({ meters, filter }: MetersProps) {
    const [searchText, setSearchText] = useState(filter.search ?? '');
    const [debouncedSearchText, setDebouncedSearchText] = useState(filter.search ?? '');

    const [showSpinner, setShowSpinner] = useState(false);
    const [showSearchResult, setShowSearchResult] = useState(false);

    const debouncedSetSearch = useDebouncedCallback(setDebouncedSearchText, {
        wait: 500,
    });

    const isVisibleSearchResult = debouncedSearchText.length > 0 && showSearchResult;

    useEffect(() => {
        router.get(
            index(),
            { search: debouncedSearchText },
            {
                onStart: () => {
                    setShowSpinner(true);
                    setShowSearchResult(false);
                },
                onFinish: () => {
                    setShowSpinner(false);
                    setShowSearchResult(true);
                },
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }, [debouncedSearchText]);

    const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setSearchText(e.target.value);
        debouncedSetSearch(e.target.value);
    };

    const handleClearSearch = () => {
        setSearchText('');
        debouncedSetSearch('');
    };

    return (
        <div className="mx-auto flex h-[calc(100dvh-3.5rem)] max-w-xs flex-col gap-2 p-2.5">
            <Button asChild variant="outline" className="w-full">
                <Link href={create()} prefetch instant>
                    <ParkingMeter data-icon="inline-start" />
                    Создать прибор учёта
                </Link>
            </Button>

            <InputGroup>
                <InputGroupInput
                    type="text"
                    value={searchText}
                    onChange={handleSearchChange}
                    placeholder="Поиск приборов учёта..."
                />
                <InputGroupAddon>{showSpinner ? <LoaderIcon className="animate-spin" /> : <Search />}</InputGroupAddon>
                {isVisibleSearchResult && <InputGroupAddon align="inline-end">{meters.length} шт.</InputGroupAddon>}
                <InputGroupAddon align="inline-end">
                    <InputGroupButton type="button" size="icon-xs" onClick={handleClearSearch}>
                        <X />
                    </InputGroupButton>
                </InputGroupAddon>
            </InputGroup>

            <ScrollArea className="flex-initial overflow-auto rounded-md border p-2.5">
                <ItemGroup className="gap-2">
                    {meters.map((meter) => (
                        <Item asChild key={meter.id} variant="outline" size="sm">
                            <Link prefetch>
                                <ItemContent className="gap-1">
                                    <ItemTitle>{meter.model}</ItemTitle>
                                    <ItemDescription>{meter.serial_number}</ItemDescription>
                                </ItemContent>
                                <ItemActions>
                                    <Eye className="size-5" />
                                </ItemActions>
                            </Link>
                        </Item>
                    ))}
                </ItemGroup>
            </ScrollArea>
        </div>
    );
}
