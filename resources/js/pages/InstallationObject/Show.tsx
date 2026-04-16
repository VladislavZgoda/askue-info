import { Link } from '@inertiajs/react';
import { Cpu, Eye, Pencil, Plus, Pyramid, Trash2, Trash2Icon, Unplug, Zap } from 'lucide-react';

import { destroy, edit } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { create, destroy as disassociateMeter } from '@/actions/App/Http/Controllers/InstallationObjectMeterController';
import { show as showMeter } from '@/actions/App/Http/Controllers/MeterController';
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
import ViewInstallationObjectsButton from '@/components/ViewInstallationObjectsButton';
import type { InstallationObjectShowProps } from '@/types';

export default function Show({ id, name, meters, uspds }: InstallationObjectShowProps) {
    return (
        <div className="mx-auto flex max-w-xs flex-col gap-6 p-2">
            <Item variant="outline">
                <ItemMedia variant="icon">
                    <Pyramid />
                </ItemMedia>
                <ItemContent>
                    <ItemTitle>{name}</ItemTitle>
                </ItemContent>
                <ItemActions>
                    <Button asChild variant="outline" size="sm" aria-label="Редактировать">
                        <Link href={edit(id)} prefetch instant cacheTags="InstallationObjectEdit">
                            <Pencil />
                        </Link>
                    </Button>
                    <AlertDialog>
                        <AlertDialogTrigger asChild>
                            <Button name="delete" variant="destructive" size="sm">
                                <Trash2 />
                            </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent size="sm">
                            <AlertDialogHeader>
                                <AlertDialogMedia className="bg-destructive/10 text-destructive dark:bg-destructive/20 dark:text-destructive">
                                    <Trash2Icon />
                                </AlertDialogMedia>
                                <AlertDialogTitle>Удалить объект установки?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Это навсегда удалит объект установки без возможности восстановления.
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

            {meters.length > 0 && (
                <ItemGroup className="max-w-xs gap-1.5">
                    {meters.map((meter) => (
                        <Item key={meter.id} variant="outline" size="sm">
                            <ItemMedia variant="icon">
                                <Zap />
                            </ItemMedia>
                            <ItemContent className="gap-1">
                                <ItemTitle>{meter.model}</ItemTitle>
                                <ItemDescription>{meter.serial_number}</ItemDescription>
                            </ItemContent>
                            <ItemActions>
                                <Button asChild variant="outline" size="sm">
                                    <Link href={showMeter(meter.id)} prefetch instant>
                                        <Eye />
                                    </Link>
                                </Button>
                                <AlertDialog>
                                    <AlertDialogTrigger asChild>
                                        <Button name="unplugMeter" variant="destructive" size="sm">
                                            <Unplug />
                                        </Button>
                                    </AlertDialogTrigger>
                                    <AlertDialogContent size="sm">
                                        <AlertDialogHeader>
                                            <AlertDialogMedia className="bg-destructive/10 text-destructive dark:bg-destructive/20 dark:text-destructive">
                                                <Unplug />
                                            </AlertDialogMedia>
                                            <AlertDialogTitle>Отсоединить прибор учёта?</AlertDialogTitle>
                                            <AlertDialogDescription>
                                                Это не удалит прибор учёта и его можно будет присоединить к любому
                                                объекту.
                                            </AlertDialogDescription>
                                        </AlertDialogHeader>
                                        <AlertDialogFooter>
                                            <AlertDialogCancel variant="outline">Отменить</AlertDialogCancel>
                                            <AlertDialogAction variant="destructive" asChild>
                                                <Link
                                                    href={disassociateMeter({
                                                        installation_object: id,
                                                        meter: meter.id,
                                                    })}
                                                >
                                                    Отсоединить
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

            {uspds.length > 0 && (
                <ItemGroup className="max-w-xs gap-1.5">
                    {uspds.map((uspd) => (
                        <Item key={uspd.id} variant="outline" size="sm">
                            <ItemMedia variant="icon">
                                <Cpu />
                            </ItemMedia>
                            <ItemContent className="gap-1">
                                <ItemTitle>{uspd.model}</ItemTitle>
                                <ItemDescription>{uspd.serial_number}</ItemDescription>
                            </ItemContent>
                            <ItemActions>
                                <Button asChild variant="outline" size="sm">
                                    <Link>
                                        <Eye />
                                    </Link>
                                </Button>
                                <Button asChild variant="outline" size="sm">
                                    <Link>
                                        <Unplug />
                                    </Link>
                                </Button>
                            </ItemActions>
                        </Item>
                    ))}
                </ItemGroup>
            )}

            <ButtonGroup orientation="vertical" className="w-full">
                <ViewInstallationObjectsButton size="sm">Список объектов установки</ViewInstallationObjectsButton>
                <Button asChild size="sm" variant="outline">
                    <Link>
                        <Plus />
                        Добавить УСПД
                    </Link>
                </Button>
                <Button asChild size="sm" variant="outline">
                    <Link href={create(id)} prefetch instant>
                        <Plus />
                        Добавить ПУ
                    </Link>
                </Button>
            </ButtonGroup>
        </div>
    );
}
