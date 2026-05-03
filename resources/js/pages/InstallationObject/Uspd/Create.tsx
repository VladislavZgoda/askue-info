import { Form } from '@inertiajs/react';
import { RotateCcwIcon, SaveIcon } from 'lucide-react';

import { store } from '@/actions/App/Http/Controllers/InstallationObjectUspdController';
import BackButton from '@/components/BackButton';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import { Spinner } from '@/components/ui/spinner';
import type { InstallationObjectUspdsProps } from '@/types';

export default function Create({ installationObject, unassignedUspds }: InstallationObjectUspdsProps) {
    return (
        <div className="mx-auto max-w-xs p-2">
            <Form<{ uspd_id: string }> action={store(installationObject.id)} instant disableWhileProcessing>
                {({ errors, processing, resetAndClearErrors }) => (
                    <Card className="mx-auto mt-2 w-full max-w-xs">
                        <CardHeader>
                            <CardTitle>Добавить УСПД к {installationObject.name}</CardTitle>
                            <CardDescription>{installationObject.address}</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Field data-invalid={errors.uspd_id ? true : false}>
                                <FieldLabel htmlFor="uspd_id">УСПД</FieldLabel>
                                <NativeSelect
                                    id="uspd_id"
                                    name="uspd_id"
                                    defaultValue=""
                                    aria-invalid={errors.uspd_id ? true : false}
                                >
                                    <NativeSelectOption value="" disabled>
                                        Выберете УСПД
                                    </NativeSelectOption>
                                    {unassignedUspds.map((uspd) => (
                                        <NativeSelectOption key={uspd.id} value={uspd.id}>
                                            {`${uspd.model} №${uspd.serial_number}`}
                                        </NativeSelectOption>
                                    ))}
                                </NativeSelect>
                                {errors.uspd_id && <FieldError>Поле является обязательным.</FieldError>}
                            </Field>
                        </CardContent>
                        <CardFooter>
                            <Field orientation="horizontal">
                                <Button disabled={processing}>
                                    {processing ? (
                                        <Spinner data-icon="inline-start" />
                                    ) : (
                                        <SaveIcon data-icon="inline-start" />
                                    )}
                                    {processing ? 'Подождите' : 'Добавить'}
                                </Button>
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => resetAndClearErrors()}
                                    disabled={processing}
                                >
                                    <RotateCcwIcon data-icon="inline-start" /> Очистить
                                </Button>
                            </Field>
                        </CardFooter>
                    </Card>
                )}
            </Form>

            <BackButton className="mt-3.5 w-full" />
        </div>
    );
}
