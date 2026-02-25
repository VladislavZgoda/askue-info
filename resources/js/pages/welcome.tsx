import { Link } from '@inertiajs/react';

import { index } from '@/actions/App/Http/Controllers/InstallationObjectController';

export default function Welcome() {
    return (
        <>
            <Link href={index()} prefetch>
                Просмотр объектов установки
            </Link>
        </>
    );
}
