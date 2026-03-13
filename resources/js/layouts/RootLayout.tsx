import type React from 'react';
import { Toaster } from 'sonner';

export default function RootLayout({ children }: { children: React.ReactNode }) {
    return (
        <>
            {children}
            <Toaster />
        </>
    );
}
