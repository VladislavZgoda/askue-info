import BackButton from '@/components/BackButton';

import FormPartial from './partials/Form';

export default function Create() {
    return (
        <div className="mx-auto max-w-xs p-2">
            <FormPartial />

            <BackButton className="mt-3.5 w-full" />
        </div>
    );
}
