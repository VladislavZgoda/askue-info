import { Link } from '@inertiajs/react';
import { Eye } from 'lucide-react';

import { show } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemTitle } from '@/components/ui/item';
import type { InstallationObjectsProps } from '@/types';

export default function InstallationObjects({ installationObjects }: InstallationObjectsProps) {
    return (
        <ItemGroup className="mt-2 ml-1 max-w-xs gap-2">
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
    );
}
