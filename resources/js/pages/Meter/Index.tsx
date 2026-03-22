import { Link } from '@inertiajs/react';
import { Eye, ParkingMeter } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemTitle } from '@/components/ui/item';
import { ScrollArea } from '@/components/ui/scroll-area';
import type { MetersProps } from '@/types';

export default function Index({ meters }: MetersProps) {
    return (
        <div className="mx-auto mt-2.5 flex h-[calc(100dvh-4rem)] w-full max-w-xs flex-col">
            <Button asChild variant="outline" className="w-full">
                <Link prefetch>
                    <ParkingMeter data-icon="inline-start" />
                    Создать прибор учёта
                </Link>
            </Button>

            <ScrollArea className="mx-auto mt-2 w-full max-w-xs flex-initial overflow-auto rounded-md border p-2.5">
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
