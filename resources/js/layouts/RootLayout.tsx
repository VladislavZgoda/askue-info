import { Link, router } from '@inertiajs/react';
import type React from 'react';
import { useEffect } from 'react';
import { toast, Toaster } from 'sonner';

import { destroy } from '@/actions/Laravel/Fortify/Http/Controllers/AuthenticatedSessionController';
import { navigationMenuTriggerStyle } from '@/components/ui/navigation-menu';

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
            <nav className="mx-auto flex w-full max-w-xs items-center justify-between p-2">
                <Link component="home" href="/" className={navigationMenuTriggerStyle()}>
                    Главная
                </Link>

                <Link href={destroy()} className={navigationMenuTriggerStyle()}>
                    Выйти
                </Link>
            </nav>
            {children}
            <Toaster />
        </main>
    );
}
