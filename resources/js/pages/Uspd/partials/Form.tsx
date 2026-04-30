import { Form, usePage } from '@inertiajs/react';
import { ArrowUp01, DecimalsArrowRight, RotateCcwIcon, SaveIcon } from 'lucide-react';

import { store, update } from '@/actions/App/Http/Controllers/UspdController';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput } from '@/components/ui/input-group';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import { Spinner } from '@/components/ui/spinner';
import type { Uspd } from '@/types';

export default function FormPartial() {
    const page = usePage<{ uspd?: Uspd }>();

    const { id, model, serial_number, lan_ip } = page.props.uspd ?? {};

    return (
        <Form<Omit<Uspd, 'id'>> action={id ? update(id) : store()} disableWhileProcessing instant>
            {({ errors, processing, resetAndClearErrors, invalid, validate }) => (
                <Card className="mx-auto mt-2 w-full max-w-xs">
                    <CardHeader>
                        <CardTitle>{id ? 'Редактировать' : 'Создать'} УСПД</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Field data-invalid={errors.model ? true : false}>
                            <FieldLabel htmlFor="model">Модель</FieldLabel>
                            <NativeSelect
                                id="model"
                                name="model"
                                defaultValue={model ?? ''}
                                aria-invalid={errors.model ? true : false}
                                onBlur={() => validate('model')}
                            >
                                <NativeSelectOption value="" disabled>
                                    Выберете модель
                                </NativeSelectOption>
                                <NativeSelectOption value="RTR8A.LRsGE-1-1-RUFG">
                                    RTR8A.LRsGE-1-1-RUFG
                                </NativeSelectOption>
                                <NativeSelectOption value="RTR8A.LRsGE-2-1-RUFG">
                                    RTR8A.LRsGE-2-1-RUFG
                                </NativeSelectOption>
                                <NativeSelectOption value="RTR8A.LGE-2-2-RUF">RTR8A.LGE-2-2-RUF</NativeSelectOption>
                                <NativeSelectOption value="RTR58A.LG-1-1">RTR58A.LG-1-1</NativeSelectOption>
                                <NativeSelectOption value="RTR58A.LG-2-1">RTR58A.LG-2-1</NativeSelectOption>
                            </NativeSelect>
                            {invalid('model') && <FieldError>{errors.model}</FieldError>}
                        </Field>
                        <Field data-invalid={errors.serial_number ? true : false} className="mt-2">
                            <FieldLabel htmlFor="serial_number">Серийный номер</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="serial_number"
                                    name="serial_number"
                                    defaultValue={serial_number}
                                    onBlur={() => validate('serial_number')}
                                />
                                <InputGroupAddon align="inline-start">
                                    <ArrowUp01 />
                                </InputGroupAddon>
                            </InputGroup>
                            {invalid('serial_number') && <FieldError>{errors.serial_number}</FieldError>}
                        </Field>
                        <Field data-invalid={errors.lan_ip ? true : false} className="mt-2">
                            <FieldLabel htmlFor="lan_ip">Lan IP</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="text"
                                    id="lan_ip"
                                    name="lan_ip"
                                    defaultValue={lan_ip}
                                    onBlur={() => validate('lan_ip')}
                                />
                                <InputGroupAddon align="inline-start">
                                    <DecimalsArrowRight />
                                </InputGroupAddon>
                            </InputGroup>
                            {invalid('lan_ip') && <FieldError>{errors.lan_ip}</FieldError>}
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
