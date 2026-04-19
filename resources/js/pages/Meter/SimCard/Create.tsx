import { Form } from '@inertiajs/react';
import { RotateCcwIcon, SaveIcon } from 'lucide-react';

import { store } from '@/actions/App/Http/Controllers/MeterSimCardController';
import BackButton from '@/components/BackButton';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldError, FieldLabel } from '@/components/ui/field';
import { NativeSelect, NativeSelectOption } from '@/components/ui/native-select';
import { Spinner } from '@/components/ui/spinner';
import type { MeterSimCardsProps } from '@/types';

export default function Create({ meter, simCards }: MeterSimCardsProps) {
    return (
        <div className="mx-auto max-w-xs p-2">
            <Form<{ sim_card_id: string }> action={store(meter.id)} instant disableWhileProcessing>
                {({ errors, processing, resetAndClearErrors }) => (
                    <Card className="mx-auto mt-2 w-full max-w-xs">
                        <CardHeader>
                            <CardTitle>Привязать сим-карту к {meter.model}</CardTitle>
                            <CardDescription>№{meter.serial_number}</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Field data-invalid={errors.sim_card_id ? true : false}>
                                <FieldLabel htmlFor="sim_card_id">Сим карта</FieldLabel>
                                <NativeSelect
                                    id="sim_card_id"
                                    name="sim_card_id"
                                    defaultValue=""
                                    aria-invalid={errors.sim_card_id ? true : false}
                                >
                                    <NativeSelectOption value="" disabled>
                                        Выберете сим-карту
                                    </NativeSelectOption>
                                    {simCards.map((simCard) => (
                                        <NativeSelectOption key={simCard.id} value={simCard.id}>
                                            {`${simCard.operator}, ${simCard.number}`}
                                        </NativeSelectOption>
                                    ))}
                                </NativeSelect>
                                {errors.sim_card_id && <FieldError>{errors.sim_card_id}</FieldError>}
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
