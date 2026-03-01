import { Link } from '@inertiajs/react';
import { Pencil, Trash2, Unplug, Eye } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { Item, ItemActions, ItemContent, ItemDescription, ItemGroup, ItemTitle } from '@/components/ui/item';
import type { InstallationObjectShowProps } from '@/types';

export default function InstallationObject({ id, name, meters, uspds }: InstallationObjectShowProps) {
    return (
        <div className="mt-1.5 ml-1 flex max-w-xs flex-col gap-6">
            <Item variant="outline">
                <ItemContent>
                    <ItemTitle>{name}</ItemTitle>
                </ItemContent>
                <ItemActions>
                    <Button asChild variant="outline" size="sm">
                        <Link>
                            <Pencil />
                        </Link>
                    </Button>
                    <Button asChild variant="outline" size="sm">
                        <Link>
                            <Trash2 />
                        </Link>
                    </Button>
                </ItemActions>
            </Item>
            <ItemGroup className="max-w-xs gap-1.5">
                {meters.map((meter) => (
                    <Item key={meter.id} variant="outline" size="sm">
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
        </div>
    );
}
