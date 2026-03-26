import { Form } from '@inertiajs/react';
import { FactoryIcon, MapPinHouseIcon, RotateCcwIcon, SaveIcon } from 'lucide-react';

import { store, update } from '@/actions/App/Http/Controllers/InstallationObjectController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldDescription, FieldError, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { InstallationObject } from '@/types';

export default function FormPartial({ id, name, address }: Partial<InstallationObject>) {
    return (
        <Form<Omit<InstallationObject, 'id'>>
            action={id ? update(id) : store()}
            invalidateCacheTags={['installationObjects', 'InstallationObjectEdit']}
            disableWhileProcessing
        >
            {({ errors, processing, resetAndClearErrors, invalid, validate }) => (
                <Card className="mx-auto mt-2 w-full max-w-xs">
                    <CardHeader>
                        <CardTitle>{id ? 'Редактировать' : 'Создать'} объект установки</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Field data-invalid={errors.name ? true : false}>
                            <FieldLabel htmlFor="name">Наименование</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="text"
                                    id="name"
                                    name="name"
                                    defaultValue={name}
                                    onBlur={() => validate('name')}
                                />
                                <InputGroupAddon align="inline-start">
                                    <FactoryIcon />
                                </InputGroupAddon>
                            </InputGroup>
                            <FieldDescription>Выберете уникальное наименование.</FieldDescription>
                            {invalid('name') && <FieldError>{errors.name}</FieldError>}
                        </Field>
                        <Field data-invalid={errors.address ? true : false} className="mt-2">
                            <FieldLabel htmlFor="address">Адрес</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="text"
                                    id="address"
                                    name="address"
                                    defaultValue={address}
                                    onBlur={() => validate('address')}
                                />
                                <InputGroupAddon align="inline-start">
                                    <MapPinHouseIcon />
                                </InputGroupAddon>
                            </InputGroup>
                            {invalid('address') && <FieldError>{errors.address}</FieldError>}
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
                                {processing ? 'Подождите' : id ? 'Изменить' : 'Создать'}
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                onClick={() => resetAndClearErrors()}
                                disabled={processing}
                            >
                                <RotateCcwIcon data-icon="inline-start" /> Сбросить
                            </Button>
                        </Field>
                    </CardFooter>
                </Card>
            )}
        </Form>
    );
}
