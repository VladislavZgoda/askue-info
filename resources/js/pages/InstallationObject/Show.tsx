import { Link, usePage } from '@inertiajs/react';
import { Cpu, Eye, MoveLeft, Pencil, Plus, Pyramid, Trash2, Unplug, Zap } from 'lucide-react';
import { useEffect } from 'react';
import { toast } from 'sonner';

import { edit } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { Button } from '@/components/ui/button';
import { ButtonGroup } from '@/components/ui/button-group';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemMedia, ItemTitle } from '@/components/ui/item';
import type { InstallationObjectShowProps } from '@/types';

export default function Show({ id, name, meters, uspds }: InstallationObjectShowProps) {
    const { flash } = usePage();

    const flashMessage = flash?.message as string | undefined;

    useEffect(() => {
        if (flashMessage) toast.success(flashMessage, { position: 'bottom-center' });
    }, [flashMessage]);

    return (
        <div className="mx-auto mt-1.5 flex max-w-xs flex-col gap-6">
            <Item variant="outline">
                <ItemMedia variant="icon">
                    <Pyramid />
                </ItemMedia>
                <ItemContent>
                    <ItemTitle>{name}</ItemTitle>
                </ItemContent>
                <ItemActions>
                    <Button asChild variant="outline" size="sm" aria-label="Редактировать">
                        <Link href={edit(id)} prefetch>
                            <Pencil />
                        </Link>
                    </Button>
                    <Button asChild variant="destructive" size="sm">
                        <Link>
                            <Trash2 />
                        </Link>
                    </Button>
                </ItemActions>
            </Item>

            <ItemGroup className="max-w-xs gap-1.5">
                {meters.map((meter) => (
                    <Item key={meter.id} variant="outline" size="sm">
                        <ItemMedia variant="icon">
                            <Zap />
                        </ItemMedia>
                        <ItemContent className="gap-1">
                            <ItemTitle>{meter.model}</ItemTitle>
                            <ItemDescription>{meter.serialNumber}</ItemDescription>
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

            <ItemGroup className="max-w-xs gap-1.5">
                {uspds.map((uspd) => (
                    <Item key={uspd.id} variant="outline" size="sm">
                        <ItemMedia variant="icon">
                            <Cpu />
                        </ItemMedia>
                        <ItemContent className="gap-1">
                            <ItemTitle>{uspd.model}</ItemTitle>
                            <ItemDescription>{uspd.serialNumber}</ItemDescription>
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

            <ButtonGroup orientation="vertical" className="w-full">
                <Button asChild size="sm" variant="outline">
                    <Link>
                        <Plus />
                        Добавить УСПД
                    </Link>
                </Button>
                <Button asChild size="sm" variant="outline">
                    <Link>
                        <Plus />
                        Добавить ПУ
                    </Link>
                </Button>
                <Button asChild size="sm" variant="outline">
                    <Link>
                        <MoveLeft />
                        Назад
                    </Link>
                </Button>
            </ButtonGroup>
        </div>
    );
}
