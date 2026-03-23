import { MoveLeft } from 'lucide-react';
import type React from 'react';

import { Button } from './ui/button';

export default function BackButton({ className, ...props }: React.ComponentPropsWithoutRef<'button'>) {
    return (
        <Button className={className} variant="outline" onClick={() => window.history.back()} {...props}>
            <MoveLeft data-icon="inline-start" />
            Назад
        </Button>
    );
}
