import { index } from '@/actions/App/Http/Controllers/MeterController';
import { Button } from '@/components/ui/button';
import { ButtonGroup } from '@/components/ui/button-group';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemMedia, ItemTitle } from '@/components/ui/item';
import type { MeterShowProps } from '@/types';
import { Link } from '@inertiajs/react';
import { CardSim, Eye, ListStart, ParkingMeter, Pencil, Plus, Trash2, Unplug } from 'lucide-react';

export default function Show({ id, model, serial_number, simCards }: MeterShowProps) {
    return (
        <div className="mx-auto flex max-w-xs flex-col gap-6 p-2">
            <Item variant="outline">
                <ItemMedia>
                    <ParkingMeter />
                </ItemMedia>
                <ItemContent>
                    <ItemTitle>{`${model} №${serial_number}`}</ItemTitle>
                </ItemContent>
                <ItemActions>
                    <Button asChild variant="outline" size="icon">
                        <Link prefetch instant>
                            <Pencil />
                        </Link>
                    </Button>
                    <Button asChild variant="destructive" size="icon">
                        <Link prefetch instant>
                            <Trash2 />
                        </Link>
                    </Button>
                </ItemActions>
            </Item>

            {simCards.length > 0 && (
                <ItemGroup className="max-w-xs gap-1.5">
                    {simCards.map((simCard) => (
                        <Item key={simCard.id} variant="outline" size="sm">
                            <ItemMedia variant="icon">
                                <CardSim />
                            </ItemMedia>
                            <ItemContent className="gap-1">
                                <ItemTitle>{`${simCard.operator}, ${simCard.number}`}</ItemTitle>
                                {simCard.ip && <ItemDescription>{`IP адрес: ${simCard.ip}`}</ItemDescription>}
                            </ItemContent>
                            <ItemActions>
                                <Button asChild variant="outline" size="icon">
                                    <Link prefetch instant>
                                        <Eye />
                                    </Link>
                                </Button>
                                <Button asChild variant="destructive" size="icon">
                                    <Link prefetch instant>
                                        <Unplug />
                                    </Link>
                                </Button>
                            </ItemActions>
                        </Item>
                    ))}
                </ItemGroup>
            )}

            <ButtonGroup orientation="vertical" className="w-full">
                <Button asChild size="sm" variant="outline">
                    <Link href={index()} prefetch instant>
                        <ListStart />
                        Просмотр приборов учёта
                    </Link>
                </Button>
                <Button asChild size="sm" variant="outline">
                    <Link>
                        <Plus />
                        Добавить сим-карту
                    </Link>
                </Button>
            </ButtonGroup>
        </div>
    );
}
