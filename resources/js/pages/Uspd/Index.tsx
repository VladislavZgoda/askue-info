import { InfiniteScroll, Link, router } from '@inertiajs/react';
import { useDebouncedCallback } from '@tanstack/react-pacer/debouncer';
import { Cpu, Eye, LoaderIcon, Search, X } from 'lucide-react';
import { useEffect, useState } from 'react';

import { index, show } from '@/actions/App/Http/Controllers/UspdController';
import { Button } from '@/components/ui/button';
import { InputGroup, InputGroupAddon, InputGroupButton, InputGroupInput } from '@/components/ui/input-group';
import { Item, ItemActions, ItemContent, ItemDescription, ItemMedia, ItemTitle } from '@/components/ui/item';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Spinner } from '@/components/ui/spinner';
import { UspdIndexProps } from '@/types';

export default function Index({ uspds, filter }: UspdIndexProps) {
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
                reset: ['uspds'],
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
                <Link prefetch instant>
                    <Cpu data-icon="inline-start" />
                    Создать УСПД
                </Link>
            </Button>

            <InputGroup>
                <InputGroupInput
                    type="text"
                    value={searchText}
                    onChange={handleSearchChange}
                    placeholder="Поиск УСПД..."
                />
                <InputGroupAddon>{showSpinner ? <LoaderIcon className="animate-spin" /> : <Search />}</InputGroupAddon>
                {isVisibleSearchResult && <InputGroupAddon align="inline-end">{uspds.data.length} шт.</InputGroupAddon>}
                <InputGroupAddon align="inline-end">
                    <InputGroupButton type="button" size="icon-xs" onClick={handleClearSearch}>
                        <X />
                    </InputGroupButton>
                </InputGroupAddon>
            </InputGroup>

            <ScrollArea className="flex-initial overflow-auto rounded-md border p-2.5">
                <InfiniteScroll
                    className="flex flex-col gap-2"
                    data="uspds"
                    onlyNext
                    loading={() => (
                        <Item>
                            <ItemMedia>
                                <Spinner />
                            </ItemMedia>
                            <ItemContent>
                                <ItemTitle className="line-clamp-1">Загрузка УСПД...</ItemTitle>
                            </ItemContent>
                        </Item>
                    )}
                >
                    {uspds.data.map((uspd) => (
                        <Item asChild key={uspd.id} variant="outline" size="sm">
                            <Link href={show(uspd.id)} prefetch instant>
                                <ItemContent className="gap-1">
                                    <ItemTitle>{uspd.model}</ItemTitle>
                                    <ItemDescription>{uspd.serial_number}</ItemDescription>
                                </ItemContent>
                                <ItemActions>
                                    <Eye className="size-5" />
                                </ItemActions>
                            </Link>
                        </Item>
                    ))}
                </InfiniteScroll>
            </ScrollArea>
        </div>
    );
}
