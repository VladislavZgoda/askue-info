import { Link } from '@inertiajs/react';

import { index } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { Button } from '@/components/ui/button';

export default function Welcome() {
    return (
        <>
            <Button asChild variant="outline" className="mt-5 ml-12">
                <Link href={index()} prefetch>
                    Просмотр объектов установки
                </Link>
            </Button>
        </>
    );
}
