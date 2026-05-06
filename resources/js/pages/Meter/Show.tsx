import { Link } from '@inertiajs/react';
import { CardSim, Eye, ListStart, Pencil, Plus, Pyramid, Trash2, Trash2Icon, Unplug, Zap } from 'lucide-react';

import { show as showInstallationObject } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { destroy, edit, index } from '@/actions/App/Http/Controllers/MeterController';
import { create, destroy as detachSimCard } from '@/actions/App/Http/Controllers/MeterSimCardController';
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
import type { MeterShowProps } from '@/types';

export default function Show({ meter }: MeterShowProps) {
    return (
        <div className="mx-auto flex max-w-xs flex-col gap-6 p-2">
            <Item variant="outline">
                <ItemMedia>
                    <Zap />
                </ItemMedia>
                <ItemContent>
                    <ItemTitle>{`${meter.model}, №${meter.serial_number}`}</ItemTitle>
                </ItemContent>
                <ItemActions>
                    <Button asChild variant="outline" size="icon">
                        <Link href={edit(meter.id)} prefetch instant>
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
                                    <Link href={destroy(meter.id)}>Удалить</Link>
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </ItemActions>
            </Item>

            {meter.simCards.length > 0 && (
                <ItemGroup className="max-w-xs gap-1.5">
                    {meter.simCards.map((simCard) => (
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
                                <AlertDialog>
                                    <AlertDialogTrigger asChild>
                                        <Button name="detachSimCard" variant="destructive" size="icon">
                                            <Unplug />
                                        </Button>
                                    </AlertDialogTrigger>
                                    <AlertDialogContent size="sm">
                                        <AlertDialogHeader>
                                            <AlertDialogMedia className="bg-destructive/10 text-destructive dark:bg-destructive/20 dark:text-destructive">
                                                <Trash2Icon />
                                            </AlertDialogMedia>
                                            <AlertDialogTitle>Отвязать сим-карту?</AlertDialogTitle>
                                            <AlertDialogDescription>
                                                Это отвяжет прибор учёта от сим-карты.
                                            </AlertDialogDescription>
                                        </AlertDialogHeader>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel variant="outline">Отменить</AlertDialogCancel>
                                            <AlertDialogAction variant="destructive" asChild>
                                                <Link href={detachSimCard({ meter: meter.id, sim_card: simCard.id })}>
                                                    Отвязать
                                                </Link>
                                            </AlertDialogAction>
                                        </AlertDialogFooter>
                                    </AlertDialogContent>
                                </AlertDialog>
                            </ItemActions>
                        </Item>
                    ))}
                </ItemGroup>
            )}

            {meter.installationObject && <h2 className="font-semibold subpixel-antialiased">Место установки:</h2>}
            {meter.installationObject && (
                <Item variant="outline" size="sm" className="max-w-xs gap-1.5">
                    <ItemMedia variant="icon">
                        <Pyramid />
                    </ItemMedia>
                    <ItemContent className="gap-1">
                        <ItemTitle>{meter.installationObject.name}</ItemTitle>
                        <ItemDescription>{meter.installationObject.address}</ItemDescription>
                    </ItemContent>
                    <ItemActions>
                        <Button asChild variant="outline" size="icon">
                            <Link href={showInstallationObject(meter.installationObject.id)} prefetch instant>
                                <Eye />
                            </Link>
                        </Button>
                    </ItemActions>
                </Item>
            )}

            <ButtonGroup orientation="vertical" className="w-full">
                <Button asChild size="sm" variant="outline">
                    <Link href={index()} prefetch instant>
                        <ListStart />
                        Просмотр приборов учёта
                    </Link>
                </Button>
                <Button asChild size="sm" variant="outline">
                    <Link href={create(meter.id)} prefetch instant>
                        <Plus />
                        Добавить сим-карту
                    </Link>
                </Button>
            </ButtonGroup>
        </div>
    );
}
