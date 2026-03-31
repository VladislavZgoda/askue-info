export interface Auth {
    user: User;
}

export interface SharedData {
    name: string;
    auth: Auth;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface InstallationObject {
    id: number;
    name: string;
    address: string;
}

export interface InstallationObjectsProps {
    installationObjects: InstallationObject[];
    filter: { search: string | null };
}

export interface InstallationObjectShowProps {
    id: InstallationObject['id'];
    name: InstallationObject['name'];
    meters: Meter[];
    uspds: Uspd[];
}

export interface Meter {
    id: number;
    model: string;
    serial_number: string;
}

export interface MetersProps {
    meters: Meter[];
    filter: { search: string | null };
}

export interface Uspd {
    id: number;
    model: string;
    serial_number: number;
}

export interface InstallationObjectMetersProps {
    installationObject: InstallationObject;
    unassignedMeters: Meter[];
}
