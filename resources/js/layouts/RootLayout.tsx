import type React from 'react';
import { Toaster } from 'sonner';

export default function RootLayout(children: React.ReactNode) {
    return (
        <>
            {children}
            <Toaster />
        </>
    );
}
