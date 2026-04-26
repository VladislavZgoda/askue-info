import { InfiniteScroll, Link, router } from '@inertiajs/react';
import { useDebouncedCallback } from '@tanstack/react-pacer/debouncer';
import { CardSim, Eye, LoaderIcon, Search, X } from 'lucide-react';
import { useEffect, useState } from 'react';

import { create, index, show } from '@/actions/App/Http/Controllers/SimCardController';
import { Button } from '@/components/ui/button';
import { InputGroup, InputGroupAddon, InputGroupButton, InputGroupInput } from '@/components/ui/input-group';
import { Item, ItemActions, ItemContent, ItemDescription, ItemMedia, ItemTitle } from '@/components/ui/item';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Spinner } from '@/components/ui/spinner';
import { SimCardIndexProps } from '@/types';

export default function Index({ simCards, filter }: SimCardIndexProps) {
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
                reset: ['simCards'],
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
                    <CardSim data-icon="inline-start" />
                    Создать сим-карту
                </Link>
            </Button>

            <InputGroup>
                <InputGroupInput
                    type="text"
                    value={searchText}
                    onChange={handleSearchChange}
                    placeholder="Поиск сим-карты..."
                />
                <InputGroupAddon>{showSpinner ? <LoaderIcon className="animate-spin" /> : <Search />}</InputGroupAddon>
                {isVisibleSearchResult && (
                    <InputGroupAddon align="inline-end">{simCards.data.length} шт.</InputGroupAddon>
                )}
                <InputGroupAddon align="inline-end">
                    <InputGroupButton type="button" size="icon-xs" onClick={handleClearSearch}>
                        <X />
                    </InputGroupButton>
                </InputGroupAddon>
            </InputGroup>

            <ScrollArea className="flex-initial overflow-auto rounded-md border p-2.5">
                <InfiniteScroll
                    className="flex flex-col gap-2"
                    data="simCards"
                    onlyNext
                    loading={() => (
                        <Item>
                            <ItemMedia>
                                <Spinner />
                            </ItemMedia>
                            <ItemContent>
                                <ItemTitle className="line-clamp-1">Загрузка SIM-карт...</ItemTitle>
                            </ItemContent>
                        </Item>
                    )}
                >
                    {simCards.data.map((simCard) => (
                        <Item asChild key={simCard.id} variant="outline" size="sm">
                            <Link href={show(simCard.id)} prefetch instant>
                                <ItemContent className="gap-1">
                                    <ItemTitle>{simCard.operator}</ItemTitle>
                                    <ItemDescription>{simCard.number}</ItemDescription>
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
