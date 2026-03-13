import '@inertiajs/core';

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        flashDataType: {
            message?: string;
        };
    }
}
