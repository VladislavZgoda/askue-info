import { Link } from '@inertiajs/react';
import { Eye, MapPlus } from 'lucide-react';

import { create, show } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { Button } from '@/components/ui/button';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemTitle } from '@/components/ui/item';
import type { InstallationObjectsProps } from '@/types';

export default function Index({ installationObjects }: InstallationObjectsProps) {
    return (
        <div className="mx-auto mt-2.5 w-full max-w-xs">
            <Button asChild variant="outline" className="w-full">
                <Link href={create()} prefetch>
                    <MapPlus data-icon="inline-start" />
                    Создать объект установки
                </Link>
            </Button>

            <ItemGroup className="mx-auto mt-2 max-w-xs gap-2">
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
        </div>
    );
}
