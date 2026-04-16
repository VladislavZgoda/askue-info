import { Link } from '@inertiajs/react';
import { CardSim, Cpu, Eye, ListStart, ParkingMeter, Pencil, Plus, Trash2, Trash2Icon, Unplug } from 'lucide-react';

import { show as showMeter } from '@/actions/App/Http/Controllers/MeterController';
import { destroy, index } from '@/actions/App/Http/Controllers/SimCardController';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogMedia,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { ButtonGroup } from '@/components/ui/button-group';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemMedia, ItemTitle } from '@/components/ui/item';
import { SimCardShowProps } from '@/types';

export default function Show({ id, number, operator, ip, meters, uspd }: SimCardShowProps) {
    return (
        <div className="mx-auto flex max-w-xs flex-col gap-6 p-2">
            <Item variant="outline">
                <ItemMedia>
                    <CardSim />
                </ItemMedia>
                <ItemContent>
                    <ItemTitle>{`${operator}, ${number}`}</ItemTitle>
                    {ip && <ItemDescription>{`IP адрес: ${ip}`}</ItemDescription>}
                </ItemContent>
                <ItemActions>
                    <Button asChild variant="outline" size="icon">
                        <Link prefetch instant>
                            <Pencil />
                        </Link>
                    </Button>
                    <AlertDialog>
                        <AlertDialogTrigger asChild>
                            <Button name="delete" variant="destructive" size="icon">
                                <Trash2 />
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent size="sm">
                            <AlertDialogHeader>
                                <AlertDialogMedia className="bg-destructive/10 text-destructive dark:bg-destructive/20 dark:text-destructive">
                                    <Trash2Icon />
                                </AlertDialogMedia>
                                <AlertDialogTitle>Удалить сим-карту?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Это навсегда удалит сим-карту без возможности восстановления.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel variant="outline">Отменить</AlertDialogCancel>
                                <AlertDialogAction variant="destructive" asChild>
                                    <Link href={destroy(id)}>Удалить</Link>
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </ItemActions>
            </Item>

            {meters.length > 0 && <h2 className="font-semibold subpixel-antialiased">Относится к следующим ПУ:</h2>}
            {meters.length > 0 && (
                <ItemGroup className="max-w-xs gap-1.5">
                    {meters.map((meter) => (
                        <Item key={meter.id} variant="outline" size="sm">
                            <ItemMedia variant="icon">
                                <ParkingMeter />
                            </ItemMedia>
                            <ItemContent className="gap-1">
                                <ItemTitle>{`${meter.model}, №${meter.serial_number}`}</ItemTitle>
                            </ItemContent>
                            <ItemActions>
                                <Button asChild variant="outline" size="icon">
                                    <Link href={showMeter(meter.id)} prefetch instant>
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

            {uspd && <h2 className="font-semibold subpixel-antialiased">Относится к УСПД:</h2>}
            {uspd && (
                <Item variant="outline" size="sm" className="max-w-xs gap-1.5">
                    <ItemMedia variant="icon">
                        <Cpu />
                    </ItemMedia>
                    <ItemContent className="gap-1">
                        <ItemTitle>{`${uspd.model}, №${uspd.serial_number}`}</ItemTitle>
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
            )}

            <ButtonGroup orientation="vertical" className="w-full">
                <Button asChild size="sm" variant="outline">
                    <Link href={index()} prefetch instant>
                        <ListStart />
                        Просмотр сим-карт
                    </Link>
                </Button>
                <Button asChild size="sm" variant="outline">
                    <Link>
                        <Plus />
                        Связать с УСПД
                    </Link>
                </Button>
                <Button asChild size="sm" variant="outline">
                    <Link>
                        <Plus />
                        Связать с ПУ
                    </Link>
                </Button>
            </ButtonGroup>
        </div>
    );
}
