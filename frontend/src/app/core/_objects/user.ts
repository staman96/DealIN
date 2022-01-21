export class User {
    user_id: string;
    user_name: string;
    user_password: string;
    email: string;
    user_status: string;/*0:pending, 1:activated*/
    user_role: string;/*0:user, 1:admin*/ 
    created_at: Date;
    updated_at: Date;

    /*user_meta*/
    fname: string;
    lname: string;
    telephone: string;
    address: string;
    vat: string;
    bidder_rating: string;
    seller_rating: string;
}