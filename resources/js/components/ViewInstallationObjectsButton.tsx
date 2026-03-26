import { Link } from '@inertiajs/react';
import type { VariantProps } from 'class-variance-authority';
import { ListStart } from 'lucide-react';
import type { ComponentPropsWithoutRef } from 'react';

import { index } from '@/routes/installation-objects';

import { Button, buttonVariants } from './ui/button';

type ButtonProps = ComponentPropsWithoutRef<'button'> & VariantProps<typeof buttonVariants>;

export default function ViewInstallationObjectsButton({ className, children, ...props }: ButtonProps) {
    return (
        <Button asChild variant="outline" {...props} className={`${className}`}>
            <Link href={index()} prefetch instant cacheTags="installationObjects">
                <ListStart data-icon="inline-start" />
                {children}
            </Link>
        </Button>
    );
}
