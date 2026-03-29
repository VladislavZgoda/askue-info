import { Form } from '@inertiajs/react';
import { ArrowUp01, ParkingMeter, RotateCcwIcon, SaveIcon } from 'lucide-react';

import { store } from '@/actions/App/Http/Controllers/MeterController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldDescription, FieldError, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { Meter } from '@/types';

export default function FormPartial() {
    return (
        <Form<Omit<Meter, 'id'>>
            action={store()}
            invalidateCacheTags={['installationObjects', 'InstallationObjectEdit']}
            disableWhileProcessing
            instant
        >
            {({ errors, processing, resetAndClearErrors, invalid, validate }) => (
                <Card className="mx-auto mt-2 w-full max-w-xs">
                    <CardHeader>
                        <CardTitle>Создать прибор учёта</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Field data-invalid={errors.model ? true : false}>
                            <FieldLabel htmlFor="model">Наименование модели</FieldLabel>
                            <InputGroup>
                                <InputGroupInput type="text" id="model" name="model" onBlur={() => validate('model')} />
                                <InputGroupAddon align="inline-start">
                                    <ParkingMeter />
                                </InputGroupAddon>
                            </InputGroup>
                            {invalid('model') && <FieldError>{errors.model}</FieldError>}
                        </Field>
                        <Field data-invalid={errors.serial_number ? true : false} className="mt-2">
                            <FieldLabel htmlFor="serial_number">Серийный номер</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="text"
                                    id="serial_number"
                                    name="serial_number"
                                    onBlur={() => validate('serial_number')}
                                />
                                <InputGroupAddon align="inline-start">
                                    <ArrowUp01 />
                                </InputGroupAddon>
                            </InputGroup>
                            <FieldDescription>Введите уникальный серийный номер.</FieldDescription>
                            {invalid('serial_number') && <FieldError>{errors.serial_number}</FieldError>}
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
                                {processing ? 'Подождите' : 'Создать'}
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
    );
}
