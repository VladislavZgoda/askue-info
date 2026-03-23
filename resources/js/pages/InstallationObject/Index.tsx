import { Link } from '@inertiajs/react';
import { Eye, MapPlus } from 'lucide-react';

import { create, show } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { Button } from '@/components/ui/button';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemTitle } from '@/components/ui/item';
import { ScrollArea } from '@/components/ui/scroll-area';
import type { InstallationObjectsProps } from '@/types';

export default function Index({ installationObjects }: InstallationObjectsProps) {
    return (
        <div className="mx-auto flex h-[calc(100dvh-3.5rem)] max-w-xs flex-col gap-2 p-2.5">
            <Button asChild variant="outline" className="w-full">
                <Link href={create()} prefetch>
                    <MapPlus data-icon="inline-start" />
                    Создать объект установки
                </Link>
            </Button>

            <ScrollArea className="flex-initial overflow-auto rounded-md border p-2.5">
                <ItemGroup className="gap-2">
                    {installationObjects.map((installationObject) => (
                        <Item asChild key={installationObject.id} variant="outline" size="sm">
                            <Link href={show(installationObject.id)} prefetch>
                                <ItemContent className="gap-1">
                                    <ItemTitle>{installationObject.name}</ItemTitle>
                                    <ItemDescription>{installationObject.address}</ItemDescription>
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
