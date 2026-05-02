import { Link } from '@inertiajs/react';
import { CardSim, Cpu, Eye, ListStart, Pencil, Plus, Pyramid, Trash2, Trash2Icon, Unplug } from 'lucide-react';

import { show as showInstallationObject } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { show as showSimCard } from '@/actions/App/Http/Controllers/SimCardController';
import { destroy, edit, index } from '@/actions/App/Http/Controllers/UspdController';
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
import { UspdShowProps } from '@/types';

export default function Show({ uspd }: UspdShowProps) {
    return (
        <div className="mx-auto flex max-w-xs flex-col gap-6 p-2">
            <Item variant="outline">
                <ItemMedia>
                    <Cpu />
                </ItemMedia>
                <ItemContent>
                    <ItemTitle>{`${uspd.model}, №${uspd.serial_number}`}</ItemTitle>
                    <ItemDescription>{`Lan IP: ${uspd.lan_ip}`}</ItemDescription>
                </ItemContent>
                <ItemActions>
                    <Button asChild variant="outline" size="icon">
                        <Link href={edit(uspd.id)} prefetch instant>
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
                                <AlertDialogTitle>Удалить УСПД?</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Это навсегда удалит УСПД без возможности восстановления.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel variant="outline">Отменить</AlertDialogCancel>
                                <AlertDialogAction variant="destructive" asChild>
                                    <Link href={destroy(uspd.id)}>Удалить</Link>
                                </AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>
                </ItemActions>
            </Item>

            {uspd.simCards.length > 0 && (
                <ItemGroup className="max-w-xs gap-1.5">
                    {uspd.simCards.map((simCard) => (
                        <Item key={simCard.id} variant="outline" size="sm">
                            <ItemMedia variant="icon">
                                <CardSim />
                            </ItemMedia>
                            <ItemContent className="gap-1">
                                <ItemTitle>{`${simCard.operator}, ${simCard.number}`}</ItemTitle>
                            </ItemContent>
                            <ItemActions>
                                <Button asChild variant="outline" size="icon">
                                    <Link href={showSimCard(simCard.id)} prefetch instant>
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

            {uspd.installationObject && <h2 className="font-semibold subpixel-antialiased">Место установки:</h2>}
            {uspd.installationObject && (
                <Item variant="outline" size="sm" className="max-w-xs gap-1.5">
                    <ItemMedia variant="icon">
                        <Pyramid />
                    </ItemMedia>
                    <ItemContent className="gap-1">
                        <ItemTitle>{uspd.installationObject.name}</ItemTitle>
                        <ItemDescription>{uspd.installationObject.address}</ItemDescription>
                    </ItemContent>
                    <ItemActions>
                        <Button asChild variant="outline" size="icon">
                            <Link href={showInstallationObject(uspd.installationObject.id)} prefetch instant>
                                <Eye />
                            </Link>
                        </Button>
                    </ItemActions>
                </Item>
            )}

            <ButtonGroup orientation="vertical" className="w-full">
                <Button asChild size="sm" variant="outline">
                    <Link>
                        <Plus />
                        Добавить сим-карту
                    </Link>
                </Button>
                <Button asChild size="sm" variant="outline">
                    <Link href={index()} prefetch instant>
                        <ListStart />
                        Просмотр УСПД
                    </Link>
                </Button>
            </ButtonGroup>
        </div>
    );
}
