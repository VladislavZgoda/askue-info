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

export interface SimCard {
    id: number;
    number: string;
    ip?: string;
    operator: 'МТС' | 'Билайн' | 'МегаФон';
}

export interface SimCardIndexProps {
    simCards: Omit<SimCard, 'ip'>[];
    filter: { search: string | null };
}

export type SimCardShowProps = SimCard & {
    meters: Meter[];
    uspd: Uspd | null;
};

export interface Meter {
    id: number;
    model: string;
    serial_number: string;
}

export interface MetersProps {
    meters: Meter[];
    filter: { search: string | null };
}

export type MeterShowProps = Meter & {
    simCards: SimCard[];
};

export interface Uspd {
    id: number;
    model: string;
    serial_number: number;
}

export interface InstallationObjectMetersProps {
    installationObject: InstallationObject;
    unassignedMeters: Meter[];
}

export interface MeterSimCardsProps {
    meter: Meter;
    simCards: Omit<SimCard, 'ip'>[];
}
