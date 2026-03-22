import { Link, router } from '@inertiajs/react';
import { useDebouncedCallback } from '@tanstack/react-pacer/debouncer';
import { Eye, ParkingMeter, Search, X } from 'lucide-react';
import { useEffect, useState } from 'react';

import { index } from '@/actions/App/Http/Controllers/MeterController';
import { Button } from '@/components/ui/button';
import { InputGroup, InputGroupAddon, InputGroupButton, InputGroupInput } from '@/components/ui/input-group';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemTitle } from '@/components/ui/item';
import { ScrollArea } from '@/components/ui/scroll-area';
import type { MetersProps } from '@/types';

export default function Index({ meters, filter }: MetersProps) {
    const [searchText, setSearchText] = useState(filter.search ?? '');
    const [debouncedSearchText, setDebouncedSearchText] = useState(filter.search ?? '');

    const debouncedSetSearch = useDebouncedCallback(setDebouncedSearchText, {
        wait: 500,
    });

    useEffect(() => {
        router.get(
            index(),
            { search: debouncedSearchText },
            {
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
        <div className="mx-auto mt-2.5 flex h-[calc(100dvh-4rem)] w-full max-w-xs flex-col gap-2">
            <Button asChild variant="outline" className="w-full">
                <Link prefetch>
                    <ParkingMeter data-icon="inline-start" />
                    Создать прибор учёта
                </Link>
            </Button>

            <InputGroup className="mx-auto max-w-xs">
                <InputGroupInput
                    type="text"
                    value={searchText}
                    onChange={handleSearchChange}
                    placeholder="Поиск приборов учёта..."
                />
                <InputGroupAddon>
                    <Search />
                </InputGroupAddon>
                {debouncedSearchText.length > 0 && (
                    <InputGroupAddon align="inline-end">{meters.length} шт.</InputGroupAddon>
                )}
                <InputGroupAddon align="inline-end">
                    <InputGroupButton type="button" size="icon-xs" onClick={handleClearSearch}>
                        <X />
                    </InputGroupButton>
                </InputGroupAddon>
            </InputGroup>

            <ScrollArea className="mx-auto w-full max-w-xs flex-initial overflow-auto rounded-md border p-2.5">
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
