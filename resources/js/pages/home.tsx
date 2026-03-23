import { Link } from '@inertiajs/react';
import { ListStart } from 'lucide-react';

import { index } from '@/actions/App/Http/Controllers/MeterController';
import { Button } from '@/components/ui/button';
import ViewInstallationObjectsButton from '@/components/ViewInstallationObjectsButton';

export default function Home() {
    return (
        <div className="mx-auto flex max-w-xs flex-col gap-2 p-2">
            <ViewInstallationObjectsButton className="w-full">
                Просмотр объектов установки
            </ViewInstallationObjectsButton>

            <Button asChild variant="outline" className="w-full">
                <Link href={index()} prefetch>
                    <ListStart data-icon="inline-start" />
                    Просмотр приборов учёта
                </Link>
            </Button>
        </div>
    );
}
