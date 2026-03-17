import { MoveLeft } from 'lucide-react';

import { Button } from './ui/button';

export default function BackButton() {
    return (
        <Button className="mt-3.5 w-full" variant="outline" onClick={() => window.history.back()}>
            <MoveLeft data-icon="inline-start" />
            Назад
        </Button>
    );
}
