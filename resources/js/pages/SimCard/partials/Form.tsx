import { Form, usePage } from '@inertiajs/react';
import { DecimalsArrowRight, Phone, RotateCcwIcon, SaveIcon } from 'lucide-react';

import { store, update } from '@/actions/App/Http/Controllers/SimCardController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import { Spinner } from '@/components/ui/spinner';
import type { SimCard } from '@/types';

export default function FormPartial() {
    const page = usePage<{ simCard?: SimCard }>();

    const { id, operator, number, ip } = page.props.simCard ?? {};

    return (
        <Form<Omit<SimCard, 'id'>> action={id ? update(id) : store()} disableWhileProcessing instant>
            {({ errors, processing, resetAndClearErrors, invalid, validate }) => (
                <Card className="mx-auto mt-2 w-full max-w-xs">
                    <CardHeader>
                        <CardTitle>{id ? 'Редактировать' : 'Создать'} сим-карту</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Field data-invalid={errors.operator ? true : false}>
                            <FieldLabel htmlFor="operator">Оператор</FieldLabel>
                            <NativeSelect
                                id="operator"
                                name="operator"
                                defaultValue={operator ?? ''}
                                aria-invalid={errors.operator ? true : false}
                                onBlur={() => validate('operator')}
                            >
                                <NativeSelectOption value="" disabled>
                                    Выберете оператора
                                </NativeSelectOption>
                                <NativeSelectOption value="МТС">МТС</NativeSelectOption>
                                <NativeSelectOption value="Билайн">Билайн</NativeSelectOption>
                                <NativeSelectOption value="МегаФон">МегаФон</NativeSelectOption>
                            </NativeSelect>
                            {invalid('operator') && <FieldError>{errors.operator}</FieldError>}
                        </Field>
                        <Field data-invalid={errors.number ? true : false} className="mt-2">
                            <FieldLabel htmlFor="number">Номер</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="text"
                                    id="number"
                                    name="number"
                                    defaultValue={number}
                                    onBlur={() => validate('number')}
                                />
                                <InputGroupAddon align="inline-start">
                                    <Phone />
                                </InputGroupAddon>
                            </InputGroup>
                            {invalid('number') && <FieldError>{errors.number}</FieldError>}
                        </Field>
                        <Field data-invalid={errors.ip ? true : false} className="mt-2">
                            <FieldLabel htmlFor="ip">IP адрес</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="text"
                                    id="ip"
                                    name="ip"
                                    defaultValue={ip}
                                    onBlur={() => validate('ip')}
                                />
                                <InputGroupAddon align="inline-start">
                                    <DecimalsArrowRight />
                                </InputGroupAddon>
                            </InputGroup>
                            {invalid('ip') && <FieldError>{errors.ip}</FieldError>}
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
                                <RotateCcwIcon data-icon="inline-start" /> Очистить
                            </Button>
                        </Field>
                    </CardFooter>
                </Card>
            )}
        </Form>
    );
}
