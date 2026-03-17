import BackButton from '@/components/BackButton';
import { ButtonGroup } from '@/components/ui/button-group';
import ViewInstallationObjectsButton from '@/components/ViewInstallationObjectsButton';
import type { InstallationObject } from '@/types';

import FormPartial from './partials/Form';

export default function Edit(installationObject: InstallationObject) {
    return (
        <>
            <FormPartial {...installationObject} />

            <ButtonGroup orientation="vertical" className="mx-auto mt-2 w-full max-w-xs rounded-md shadow-sm">
                <ViewInstallationObjectsButton>Список объектов установки</ViewInstallationObjectsButton>
                <BackButton />
            </ButtonGroup>
        </>
    );
}
