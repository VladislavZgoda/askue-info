import BackButton from '@/components/BackButton';

import FormPartial from './partials/Form';

export default function Create() {
    return (
        <div className="mx-auto max-w-xs">
            <FormPartial />
            <BackButton />
        </div>
    );
}
