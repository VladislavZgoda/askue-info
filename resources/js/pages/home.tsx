import { Link } from '@inertiajs/react';
import { ListStart } from 'lucide-react';

import { index } from '@/actions/App/Http/Controllers/MeterController';
import { Button } from '@/components/ui/button';
import ViewInstallationObjectsButton from '@/components/ViewInstallationObjectsButton';

export default function Home() {
    return (
        <div className="mt-5 flex flex-col gap-2">
            <ViewInstallationObjectsButton className="mx-auto w-full max-w-xs">
                Просмотр объектов установки
            </ViewInstallationObjectsButton>

            <Button asChild variant="outline" className="mx-auto w-full max-w-xs">
                <Link href={index()}>
                    <ListStart data-icon="inline-start" />
                    Просмотр приборов учёта
                </Link>
            </Button>
        </div>
    );
}
