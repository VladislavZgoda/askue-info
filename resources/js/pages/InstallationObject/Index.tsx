import type { InstallationObjectsProps } from '@/types';

export default function InstallationObjects({ installationObjects }: InstallationObjectsProps) {
    const listItems = installationObjects.map((installationObject) => (
        <li key={installationObject.id}>
            <p>{installationObject.name}</p>
            <p>{installationObject.address}</p>
            <p className="mb-4">{installationObject.type}</p>
        </li>
    ));

    return <ul className="mt-1 ml-3">{listItems}</ul>;
}
