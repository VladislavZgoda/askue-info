import { Link } from '@inertiajs/react';
import { ListStart } from 'lucide-react';
import type { ComponentPropsWithoutRef } from 'react';

import { index } from '@/routes/installation-objects';

import { Button } from './ui/button';

export default function ViewInstallationObjectsButton({
    className,
    children,
    ...props
}: ComponentPropsWithoutRef<'button'>) {
    return (
        <Button asChild variant="outline" {...props} className={`${className}`}>
            <Link href={index()} prefetch cacheTags="installationObjects">
                <ListStart data-icon="inline-start" />
                {children}
            </Link>
        </Button>
    );
}
