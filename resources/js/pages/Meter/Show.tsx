import { Link } from '@inertiajs/react';
import { CardSim, Eye, ListStart, Pencil, Plus, Trash2, Trash2Icon, Unplug, Zap } from 'lucide-react';

import { edit, index } from '@/actions/App/Http/Controllers/MeterController';
import { show } from '@/actions/App/Http/Controllers/SimCardController';
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
import { destroy } from '@/routes/meters';
import type { MeterShowProps } from '@/types';

export default function Show({ id, model, serial_number, simCards }: MeterShowProps) {
    return (
        <div className="mx-auto flex max-w-xs flex-col gap-6 p-2">
            <Item variant="outline">
                <ItemMedia>
                    <Zap />
                </ItemMedia>
                <ItemContent>
                    <ItemTitle>{`${model} №${serial_number}`}</ItemTitle>
                </ItemContent>
                <ItemActions>
                    <Button asChild variant="outline" size="icon">
                        <Link href={edit(id)} prefetch instant>
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
                                <AlertDialogTitle>Удалить прибор учёта?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Это навсегда удалит прибор учёта без возможности восстановления.
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
                                    <Link href={show(simCard.id)} prefetch instant>
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
