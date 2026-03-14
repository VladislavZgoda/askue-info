import ViewInstallationObjectsButton from '@/components/ViewInstallationObjectsButton';

export default function Home() {
    return (
        <div className="mt-5 flex">
            <ViewInstallationObjectsButton className="mx-auto w-full max-w-xs">
                Просмотр объектов установки
            </ViewInstallationObjectsButton>
        </div>
    );
}
