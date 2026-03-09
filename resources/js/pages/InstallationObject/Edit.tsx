import { Form, Link } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { ButtonGroup } from '@/components/ui/button-group';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldDescription, FieldError, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
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
                            <Field data-invalid={errors.name}>
                                <FieldLabel htmlFor="name">Наименование</FieldLabel>
                                <Input type="text" id="name" name="name" defaultValue={name} data-invalid={errors.name} />
                                <FieldDescription>Выберете уникальное наименование.</FieldDescription>
                                {errors.name && <FieldError>{errors.name}</FieldError>}
                            </Field>
                            <Field data-invalid={errors.address} className="mt-2">
                                <FieldLabel htmlFor="address">Адрес</FieldLabel>
                                <Input type="text" id="address" name="address" defaultValue={address} data-invalid={errors.address} />
                                {errors.address && <FieldError>{errors.address}</FieldError>}
                            </Field>
                        </CardContent>
                        <CardFooter>
                            <Field orientation="horizontal">
                                <Button disabled={processing}>
                                    {processing && <Spinner data-icon="inline-start" />}
                                    {processing ? 'Подождите' : 'Изменить'}
                                </Button>
                                <Button type="button" variant="outline" onClick={() => reset()} disabled={processing}>
                                    Сбросить
                                </Button>
                            </Field>
                        </CardFooter>
                    </Card>
                )}
            </Form>

            <ButtonGroup orientation="vertical" className="mx-auto mt-2 w-full max-w-xs rounded-md shadow-sm">
                <Button asChild variant="outline">
                    <Link href={index()} prefetch>
                        Список объектов
                    </Link>
                </Button>
                <Button asChild variant="outline">
                    <Link href={show(id)} prefetch>
                        Назад
                    </Link>
                </Button>
            </ButtonGroup>
        </>
    );
}
