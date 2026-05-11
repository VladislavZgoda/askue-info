import { Form } from '@inertiajs/react';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldError, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { store } from '@/routes/login';

export default function Login() {
    return (
        <div className="flex min-h-screen w-full items-center justify-center p-2">
            <Form<{ email: string; password: string }>
                {...store.form()}
                disableWhileProcessing
                resetOnSuccess={['password']}
                className="w-full max-w-xs"
            >
                {({ errors, resetAndClearErrors }) => (
                    <Card>
                        <CardHeader>
                            <CardTitle>Войдите в свой аккаунт</CardTitle>
                            <CardDescription>
                                Введите свой адрес электронной почты ниже, чтобы войти в свой аккаунт.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <FieldGroup>
                                <Field data-invalid={errors.email ? true : false}>
                                    <FieldLabel htmlFor="email">Email</FieldLabel>
                                    <Input id="email" type="email" name="email" placeholder="m@example.ru" required />
                                    {errors.email && <FieldError>{errors.email}</FieldError>}
                                </Field>
                                <Field data-invalid={errors.password ? true : false}>
                                    <div className="flex items-center">
                                        <FieldLabel htmlFor="password">Пароль</FieldLabel>
                                    </div>
                                    <Input id="password" type="password" name="password" required />
                                    {errors.password && <FieldError>{errors.password}</FieldError>}
                                </Field>
                            </FieldGroup>
                        </CardContent>
                        <CardFooter>
                            <Field orientation="horizontal" className="justify-between">
                                <Button type="submit" className="w-25">
                                    Войти
                                </Button>
                                <Button type="button" variant="outline" onClick={() => resetAndClearErrors()}>
                                    Очистить
                                </Button>
                            </Field>
                        </CardFooter>
                    </Card>
                )}
            </Form>
        </div>
    );
}
