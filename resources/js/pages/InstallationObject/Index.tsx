import type { InstallationObjectsProps } from '@/types';

export default function InstallationObjects({ installationObjects }: InstallationObjectsProps) {
    const listItems = installationObjects.map((installationObject) => (
        <li key={installationObject.id} className="mb-1.5 flex gap-3">
            <p>{installationObject.name}</p>
            <p>{installationObject.address}</p>
        </li>
    ));

    return <ul className="mt-1 ml-3">{listItems}</ul>;
}
