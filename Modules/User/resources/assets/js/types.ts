export type UserRole = 'admin' | 'editor' | 'customer';

export type UserItem = {
    id: number;
    name: string;
    email: string;
    role: UserRole;
    role_label: string;
    created_at: string | null;
};
