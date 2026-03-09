import { Form, Link } from '@inertiajs/react';
import { FactoryIcon, ListStart, MapPinHouseIcon, MoveLeft, RotateCcwIcon, SaveIcon } from 'lucide-react';

import { Button } from '@/components/ui/button';
import { ButtonGroup } from '@/components/ui/button-group';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldDescription, FieldError, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import { index, show, update } from '@/routes/installation-objects';
import type { InstallationObject } from '@/types';

export default function Edit({ id, name, address }: InstallationObject) {
    return (
        <>
            <Form action={update(id)} setDefaultsOnSuccess disableWhileProcessing>
                {({ errors, processing, reset }) => (
                    <Card className="mx-auto mt-2 w-full max-w-xs">
                        <CardHeader>
                            <CardTitle>Редактировать объект установки</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <Field data-invalid={errors.name ? true : false}>
                                <FieldLabel htmlFor="name">Наименование</FieldLabel>
                                <InputGroup>
                                    <InputGroupInput type="text" id="name" name="name" defaultValue={name} />
                                    <InputGroupAddon align="inline-start">
                                        <FactoryIcon />
                                    </InputGroupAddon>
                                </InputGroup>
                                <FieldDescription>Выберете уникальное наименование.</FieldDescription>
                                {errors.name && <FieldError>{errors.name}</FieldError>}
                            </Field>
                            <Field data-invalid={errors.address ? true : false} className="mt-2">
                                <FieldLabel htmlFor="address">Адрес</FieldLabel>
                                <InputGroup>
                                    <InputGroupInput type="text" id="address" name="address" defaultValue={address} />
                                    <InputGroupAddon align="inline-start">
                                        <MapPinHouseIcon />
                                    </InputGroupAddon>
                                </InputGroup>
                                {errors.address && <FieldError>{errors.address}</FieldError>}
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
                                    {processing ? 'Подождите' : 'Изменить'}
                                </Button>
                                <Button type="button" variant="outline" onClick={() => reset()} disabled={processing}>
                                    <RotateCcwIcon data-icon="inline-start" /> Сбросить
                                </Button>
                            </Field>
                        </CardFooter>
                    </Card>
                )}
            </Form>

            <ButtonGroup orientation="vertical" className="mx-auto mt-2 w-full max-w-xs rounded-md shadow-sm">
                <Button asChild variant="outline">
                    <Link href={index()} prefetch>
                        <ListStart data-icon="inline-start" /> Список объектов
                    </Link>
                </Button>
                <Button asChild variant="outline">
                    <Link href={show(id)} prefetch>
                        <MoveLeft data-icon="inline-start" /> Назад
                    </Link>
                </Button>
            </ButtonGroup>
        </>
    );
}
