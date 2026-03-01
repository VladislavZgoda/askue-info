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
}

export interface InstallationObjectShowProps {
    id: InstallationObject['id'];
    name: InstallationObject['name'];
    meters: {
        id: number;
        model: string;
        serialNumber: string;
    }[];
    uspds: {
        id: number;
        model: string;
        serialNumber: number;
    }[];
}
