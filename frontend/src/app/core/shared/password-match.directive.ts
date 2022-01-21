import { Directive } from '@angular/core';
import { Validator, NG_VALIDATORS } from '@angular/forms';

@Directive({
  providers: [{provide: NG_VALIDATORS, useExisting: PasswordMatchDirective , multi: true}],
  selector: '[appPasswordMatch]'
})
export class PasswordMatchDirective implements Validator{
  validate(control: import("@angular/forms").AbstractControl): import("@angular/forms").ValidationErrors {
    throw new Error("Method not implemented.");
  }  registerOnValidatorChange?(fn: () => void): void {
    throw new Error("Method not implemented.");
  }
}
