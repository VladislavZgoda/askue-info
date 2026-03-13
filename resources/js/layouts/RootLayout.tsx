import { router } from '@inertiajs/react';
import type React from 'react';
import { useEffect } from 'react';
import { toast, Toaster } from 'sonner';

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
        <>
            {children}
            <Toaster />
        </>
    );
}
