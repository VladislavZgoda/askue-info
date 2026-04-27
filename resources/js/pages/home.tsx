import { Link } from '@inertiajs/react';
import { ListStart } from 'lucide-react';

import { index } from '@/actions/App/Http/Controllers/MeterController';
import { index as simCardIndex } from '@/actions/App/Http/Controllers/SimCardController';
import { index as uspdIndex } from '@/actions/App/Http/Controllers/UspdController';
import { Button } from '@/components/ui/button';
import ViewInstallationObjectsButton from '@/components/ViewInstallationObjectsButton';

export default function Home() {
    return (
        <div className="mx-auto flex max-w-xs flex-col gap-2 p-2">
            <ViewInstallationObjectsButton className="w-full">
                Просмотр объектов установки
            </ViewInstallationObjectsButton>

            <Button asChild variant="outline" className="w-full">
                <Link href={index()} prefetch instant>
                    <ListStart data-icon="inline-start" />
                    Просмотр приборов учёта
                </Link>
            </Button>

            <Button asChild variant="outline" className="w-full">
                <Link href={simCardIndex()} prefetch instant>
                    <ListStart data-icon="inline-start" />
                    Просмотр сим-карт
                </Link>
            </Button>

            <Button asChild variant="outline" className="w-full">
                <Link href={uspdIndex()} prefetch instant>
                    <ListStart data-icon="inline-start" />
                    Просмотр УСПД
                </Link>
            </Button>
        </div>
    );
}
