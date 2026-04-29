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
    simCards: {
        data: Omit<SimCard, 'ip'>[];
    };
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
    model: 'RTR8A.LRsGE-1-1-RUFG' | 'RTR8A.LRsGE-2-1-RUFG' | 'RTR8A.LGE-2-2-RUF' | 'RTR58A.LG-1-1' | 'RTR58A.LG-2-1';
    serial_number: number;
    lan_ip: string;
}

export interface UspdIndexProps {
    uspds: {
        data: Omit<Uspd, 'lan_ip'>[];
    };
    filter: { search: string | null };
}

export interface UspdShowProps {
    uspd: Uspd & {
        simCards: Omit<SimCard, 'ip'>[];
        installationObject?: InstallationObject;
    };
}

export interface InstallationObjectMetersProps {
    installationObject: InstallationObject;
    unassignedMeters: Meter[];
}

export interface MeterSimCardsProps {
    meter: Meter;
    simCards: Omit<SimCard, 'ip'>[];
}
