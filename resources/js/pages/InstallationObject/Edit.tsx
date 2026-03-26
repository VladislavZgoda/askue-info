import BackButton from '@/components/BackButton';
import { ButtonGroup } from '@/components/ui/button-group';
import ViewInstallationObjectsButton from '@/components/ViewInstallationObjectsButton';

import FormPartial from './partials/Form';

export default function Edit() {
    return (
        <div className="mx-auto max-w-xs p-2">
            <FormPartial />

            <ButtonGroup orientation="vertical" className="mt-2 w-full rounded-md shadow-sm">
                <ViewInstallationObjectsButton>Список объектов установки</ViewInstallationObjectsButton>
                <BackButton className="w-full" />
            </ButtonGroup>
        </div>
    );
}
