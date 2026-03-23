import { Link, router } from '@inertiajs/react';
import type React from 'react';
import { useEffect } from 'react';
import { toast, Toaster } from 'sonner';

import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from '@/components/ui/navigation-menu';

export default function RootLayout({ children }: { children: React.ReactNode }) {
    useEffect(() => {
        return router.on('flash', (event) => {
            const flashMessage = event.detail.flash.message;

            if (flashMessage) {
                toast.success(flashMessage, { position: 'bottom-center' });
            }
        });
    }, []);

    return (
        <main className="h-dvh">
            <NavigationMenu className="mx-auto w-full max-w-xs justify-start p-2">
                <NavigationMenuList>
                    <NavigationMenuItem>
                        <NavigationMenuLink asChild className={navigationMenuTriggerStyle()}>
                            <Link href="/">Главная</Link>
                        </NavigationMenuLink>
                    </NavigationMenuItem>
                </NavigationMenuList>
            </NavigationMenu>
            {children}
            <Toaster />
        </main>
    );
}
