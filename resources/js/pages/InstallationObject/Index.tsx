import { Link } from '@inertiajs/react';
import { View } from 'lucide-react';

import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemTitle } from '@/components/ui/item';
import type { InstallationObjectsProps } from '@/types';

export default function InstallationObjects({ installationObjects }: InstallationObjectsProps) {
    return (
        <ItemGroup className="mt-2 ml-1 max-w-xs gap-2">
            {installationObjects.map((installationObject) => (
                <Item asChild key={installationObject.id} variant="outline" size="sm">
                    <Link>
                        <ItemContent className="gap-1">
                            <ItemTitle>{installationObject.name}</ItemTitle>
                            <ItemDescription>{installationObject.address}</ItemDescription>
                        </ItemContent>
                        <ItemActions>
                            <View className="size-4" />
                        </ItemActions>
                    </Link>
                </Item>
            ))}
        </ItemGroup>
    );
}
